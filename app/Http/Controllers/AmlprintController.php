<?php

namespace App\Http\Controllers;

use App\Helpers\CsvHelper;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class AmlprintController extends Controller
{
  function search(Request $request)
  {
    $csv = 'Type Tx,Id Tx,Main DNI,Main Name,Date Tx,Cur,Amount,Source/Destination wallet,Reference,Sourde/Destination DNI,Source/Destination Name,Status';

    $dni = $request->input('dni');
    $fullname = $request->input('fullname');
    $fecha = $request->input('fecha');
    $referencia = $request->input('referencia');
    $date_from = $request->input('date_from');
    $date_to = $request->input('date_to');

    $data = [];
    $data['dni'] = $dni;
    $data['fullname'] = $fullname;
    $data['fecha'] = $fecha;
    $data['referencia'] = $referencia;

//DATOS DE LA PERSONA
$query = DB::table('locations_commercephone', 'lc')
->select(DB::raw('cc.id, cc.created  as fechacreacion, dni, number, name, cc.active'))
->join('locations_phone as lp', 'lc.phone_ptr_id', '=', 'lp.id')
->join('commerces_commerce as cc',  'lc.commerce_id', '=', 'cc.id')
->limit(5000);

if (!empty($dni)) {
  $dnis = [];

  $dnis = preg_split("/\r\n|\n|\r/", $dni);

  //$query->whereIn('cc.dni', $dnis);
  $query->where('cc.dni', '=', (string)$dni);
}
$results = $query->get();
foreach($results as $result)
{
    $data['fechacreacion'] = $result->fechacreacion;
    $data['numero'] = $result->number;
    $data['nombre'] = $result->name;
    if($result->active)
    $data['estado'] = 'Activo';
    else
    $data['estado'] = 'Inactivo';

}


//balance de banco
$query = "SELECT mc.code as name, SUM(balance.total) from(
  with xid as (select id from commerces_commerce cc where cc.dni = '$dni')
  select 1 as ordering, 'tt' as source, tt.fiat_amount_to_commerce as total, tt.currency_id FROM transactions_transaction tt where tt.commerce_id = (table xid) and tt.status = 'approved' and tt.fiat_amount_to_commerce > 0
  union all
  select 2, 'mw', (mw.amount * -1), mw.currency_id from money_withdrawals mw WHERE mw.commerce_id = (table xid) and mw.status not in ('rejected','cancelled') and mw.amount > 0
  union all
  select 3, 'mt', (mt.amount *  1), mt.currency_id from money_transfer mt WHERE mt.commerce_to_id = (table xid) and mt.status not in ('cancelled') and mt.amount > 0
  union all
  select 4, 'mt', (mt.amount * -1), mt.currency_id from money_transfer mt WHERE mt.commerce_from_id = (table xid) and mt.status not in ('cancelled') and mt.amount > 0
  union all
  select 5, 'md', md.amount, md.currency_id from money_deposit md WHERE md.commerce_id = (table xid) and md.status in ('approved') and md.amount > 0
  union all
  select 6, 'me', (me.amount*-1), me.currency_from_id FROM money_exchange me WHERE me.commerce_id = (table xid) and me.status in ('approved') and me.total > 0
  union all
  select 7, 'me', (me.total*1), me.currency_to_id FROM money_exchange me WHERE me.commerce_id = (table xid) and me.status in ('approved') and me.total > 0
  union all
  select 8, 'mp', (mp.amount*-1), mp.currency_id FROM money_purchase mp WHERE mp.commerce_id = (table xid) and mp.status in ('initial', 'approved') and mp.amount > 0
  order by ordering
  ) balance
  join money_currency mc on mc.id = balance.currency_id
  group  by mc.id
  ";

$results = DB::select($query);

$data['balances'] = $results;

    //retiro banco
    $query = DB::table('commerces_commerce', 'cc')
      ->select(DB::raw("'Retiro Banco' as tipo, mw.id as idtx, cc.dni ,cc.name , mw.created, mc.code as moneda ,  round(mw.amount,2) as monto, mw.wallet, mba.number as reference, mba.id  as iddestino, mb.name as destino, mw.status"))
      ->join('money_withdrawals as mw', 'cc.id', '=', 'mw.commerce_id')
      ->join('money_currency as mc',  'mc.id', '=', 'mw.currency_id')
      ->join('money_bankaccount as mba', 'mba.id', '=', 'mw.account_id')
      ->join('money_bank as mb',  'mba.bank_id', '=', 'mb.id')
      ->limit(5000);
 
    if (!empty($dni)) {
      $dnis = [];
 
      $dnis = preg_split("/\r\n|\n|\r/", $dni);
 
      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }
 
    $results = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results->toArray());
    $data['retirobanco'] = $results;

    //retiro wallet chivo
    $query = DB::table('commerces_commerce', 'cc')
      ->select(DB::raw("'Retiro wallet chivo' as tipo, mw.id as idtx, cc.dni, cc.name, mw.created , mc.code as moneda ,round(mw.amount,8) as monto, mw.wallet, mw.reference, cc2.dni as iddestino, cc2.name as destino, mw.status "))
      ->join('money_withdrawals as mw', 'cc.id', '=', 'mw.commerce_id')
      ->join('money_deposit as md',  'md.wallet', '=', 'mw.wallet')
      ->join('money_currency as mc',  'mw.currency_id', '=', 'mc.id')
      ->join('commerces_commerce as cc2',  'cc2.id', '=', 'md.commerce_id')
      ->whereRaw("md.wallet <> ''")
      ->limit(5000);

    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }
    $query->where('mw.wallet', '!=', '');
    $query->where('commission', '<=', 0);
    $results2 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results2->toArray());
    $data['retirowallet'] = $results2;


   

//retiro wallet externa
$query = DB::table('commerces_commerce', 'cc')
->select(DB::raw("'Retiro wallet externa' as tipo, mw.id as idtx, cc.dni, cc.name, mw.created ,  mc.code as moneda, round(mw.amount,8) as monto, mw.wallet, mw.reference, mw.account_id as iddestino, mw.wallet  as destino,mw.status  "))
->join('money_withdrawals as mw', 'cc.id', '=', 'mw.commerce_id')
->join('money_currency as mc',  'mw.currency_id', '=', 'mc.id')
->limit(5000);
$query->where('mw.currency_id', '=', 1);
 $query->where('commission', '>', 0);
if (!empty($dni)) {
$dnis = [];

$dnis = preg_split("/\r\n|\n|\r/", $dni);

//$query->whereIn('cc.dni', $dnis);
$query->where('cc.dni', '=', (string)$dni);
}

$results2 = $query->get();
$csv .= PHP_EOL . CsvHelper::array2csv($results2->toArray());
$data['retirowalletexterna'] = $results2;



    //compras btc
    $query = DB::table('money_purchase', 'mp')
      ->select(DB::raw("'Compras en comercio' as tipo, mp.id as idtx, cc.dni, cc.name , mp.created, mc.code as moneda , amount as monto, tt.wallet , tt.reference, cc3.dni as iddestino,  cc3.name  as destino,mp.status"))
      ->join('money_currency  as mc', 'mp.currency_id', '=', 'mc.id')
      ->join('commerces_commerce as cc',  'mp.commerce_id', '=', 'cc.id')
      ->join('transactions_transaction as tt',  'mp.transaction_id', '=', 'tt.id')
      ->join('commerces_commerce as cc3',  'tt.commerce_id', '=', 'cc3.id')
      ->limit(5000);
    $query->where('mp.currency_id',  '=',  1);
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }
    $results3 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results3->toArray());
    $data['comprasbtc'] = $results3;


    


    //compras dolares
    $query = DB::table('money_purchase', 'mp')
      ->select(DB::raw("'Compras en comercio' as tipo, mp.id as idtx, cc.dni, cc.name , mp.created, mc.code as moneda , amount as monto, tt.wallet , tt.reference, cc3.dni as iddestino,  cc3.name  as destino,mp.status"))
      ->join('money_currency  as mc', 'mp.currency_id', '=', 'mc.id')
      ->join('commerces_commerce as cc',  'mp.commerce_id', '=', 'cc.id')
      ->join('transactions_transaction as tt',  'mp.transaction_id', '=', 'tt.id')
      ->join('commerces_commerce as cc3',  'tt.commerce_id', '=', 'cc3.id')
      ->limit(5000);
    $query->where('mp.currency_id',  '=',  25);
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }
    $results4 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results4->toArray());
    $data['comprasusd'] = $results4;

    //transferencias recibidas
     $query = DB::table('commerces_commerce', 'cc')
      ->select(DB::raw("'Transaccion recibido de usuario chivo' as tipo, mt.id as idtx , cc.dni, cc.name, mt.created, mc.code as moneda, round( mt.amount,8) as monto, '' as wallet, mt.comment as reference, cc2.dni as iddestino, cc2.name  as destino, mt.status"))
      ->join('money_transfer as mt', 'mt.commerce_to_id', '=', 'cc.id')
      ->join('commerces_commerce as cc2',  'cc2.id', '=', 'mt.commerce_from_id')
      ->join('money_currency as mc',  'mc.id', '=', 'mt.currency_id')
      ->limit(5000);
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['txrecibidas'] = $results5;

    //transferencias realizadas
    $query = DB::table('commerces_commerce', 'cc')
      ->select(DB::raw("'Transaccion realizada a usuario chivo' as tipo, mt.id as idtx, cc.dni, cc.name, mt.created, mc.code as moneda, round(mt.amount,8) as monto, ''as wallet, mt.comment as reference, cc2.dni  as iddestino, cc2.name as destino, mt.status"))
      ->join('money_transfer as mt', 'mt.commerce_from_id', '=', 'cc.id')
      ->join('commerces_commerce as cc2',  'cc2.id', '=', 'mt.commerce_to_id')
      ->join('money_currency as mc',  'mc.id', '=', 'mt.currency_id')
      ->limit(5000);
    //$query->where('mt.status', '=', 'approved');
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['txrealizadas'] = $results5;

    //depostios btc internos
    $query = DB::table('commerces_commerce', 'cc')
      ->select(DB::raw("'Deposito usuario chivo' as tipo, md.id as idtx, cc.dni, cc.name, md.modified as created, mc.code as moneda , round(md.amount,8) as monto, md.wallet, md.reference, cc2.dni as iddestino, cc2.name as destino , md.status"))
      ->join('money_deposit as md', 'md.commerce_id', '=', 'cc.id')
      ->join('money_withdrawals as mw',  'mw.wallet', '=', 'md.wallet')
      ->join('commerces_commerce as cc2',  'cc2.id', '=', 'mw.commerce_id')
      ->join('money_currency as mc',  'mc.id', '=', 'md.currency_id')
      ->limit(5000);
    //$query->where('md.status', '=', 'approved');
    $query->where('md.currency_id', '=', 1);
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['depositosbtcinterno'] = $results5;



    //depostios btc bono de registro
    $query = $query = DB::table('money_deposit', 'md')
      ->select(DB::raw("'Bono de registro' as tipo, md.id as idtx, ' ' as dni, md.comment as name, md.modified as created, mc.code as moneda, md.amount as monto, md.wallet, md.reference, cc.dni  as iddestino, cc.name as destino, md.status"))
      ->join('commerces_commerce as cc', 'cc.id', '=', 'md.commerce_id')
      ->join('money_currency as mc',  'mc.id', '=', 'md.currency_id')
      ->limit(5000);
    $query->where('md.comment', '=', 'Bono de registro');
    
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['depositosbtcbono'] = $results5;


    //depostios btc externo
    $query = $query = DB::table('money_deposit', 'md')
      ->select(DB::raw("'Depostio wallet externa' as tipo, md.id as idtx, cc.dni, cc.name, md.modified as created, mc.code as moneda, md.amount as monto, md.wallet, md.reference, ' '  as iddestino, ' ' as destino, md.status"))
      ->join('commerces_commerce as cc', 'cc.id', '=', 'md.commerce_id')
      ->join('money_currency as mc',  'mc.id', '=', 'md.currency_id')
      ->limit(5000);
    $query->where('md.amount', '>', 0);
    $query->Where('md.confirmations', '>', 0);
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['depositosbtcexterno'] = $results5;

    //depostios usd
    $query = $query = DB::table('money_deposit', 'md')
      ->select(DB::raw("'Depostio tarjeta de credito' as tipo, md.id as idtx, ' ' as dni, ' ' as name, md.modified as created, mc.code as moneda, md.amount as monto, ' ' as wallet, ' ' as reference, cc.dni  as iddestino, cc.name as destino, md.status"))
      ->join('commerces_commerce as cc', 'cc.id', '=', 'md.commerce_id')
      ->join('money_currency as mc',  'mc.id', '=', 'md.currency_id')
      ->limit(5000);
    $query->where('md.currency_id', '=', 25);
    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['depositosusd'] = $results5;

    //pagos recibidos
   $query = DB::table('money_purchase', 'mp')
      ->select(DB::raw("'Pago por venta realizada' as tipo, mp.id  as idtx, cc3.dni as dni, cc3.name, mp.created, mc.code as moneda, round(tt.fiat_amount_to_commerce,8) as monto, tt.wallet , tt.reference , cc.dni as iddestino, cc.name as destino,  mp.status"))
      ->join('commerces_commerce as cc', 'cc.id', '=', 'mp.commerce_id')
      ->join('transactions_transaction as tt', 'mp.transaction_id', '=', 'tt.id')
      ->join('money_currency as mc',  'tt.currency_id', '=', 'mc.id')
      ->join('commerces_commerce as cc3',  'tt.commerce_id', '=', 'cc3.id')

      ->limit(5000);

    if (!empty($dni)) {
      $dnis = [];

      $dnis = preg_split("/\r\n|\n|\r/", $dni);

      //$query->whereIn('cc.dni', $dnis);
      $query->where('cc3.dni', '=', (string)$dni);
    }

    $results5 = $query->get();
    $csv .= PHP_EOL . CsvHelper::array2csv($results5->toArray());

    $data['pagosrecibidos'] = $results5;

    //$csv = CsvHelper::array2csv($results->toArray());
    //$data['csv'] = "csv/aml.search.{$date_from}.{$date_to}_at_" . date('Y-m-d H.i.s') . '.csv';
    //file_put_contents($data['csv'], $csv);

    $data['csv'] = "csv/amlprint.search.{$date_from}.{$date_to}_at_" . date('Y-m-d H.i.s') . '.csv';
    file_put_contents($data['csv'], $csv);

    return view('amlprint',  $data);
  }
}
