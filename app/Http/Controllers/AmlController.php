<?php

namespace App\Http\Controllers;

use App\Helpers\CsvHelper;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class AmlController extends Controller
{
  function search(Request $request)
  {
    $csv = 'Type Tx,Id Tx,Main DNI,Main Name,Date Tx,Cur,Amount,Source/Destination wallet,Reference,Sourde/Destination DNI,Source/Destination Name,Status';

    $dni = $request->input('dni');
    $date_from = $request->input('date_from');
    $date_to = $request->input('date_to');

    $data = [];
    $data['dni'] = $dni;
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
      ->select(DB::raw("'Retiro Wallet' as tipo, mw.id as idtx, cc.dni, cc.name, mw.created , mc.code as moneda ,round(mw.amount,8) as monto, mw.wallet, mw.reference, cc2.dni as iddestino, cc2.name as destino, mw.status "))
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
->select(DB::raw("'Retiro Wallet Externa' as tipo, mw.id as idtx, cc.dni, cc.name, mw.created ,  mc.code as moneda, round(mw.amount,8) as monto, mw.wallet, mw.reference, mw.account_id as iddestino, mw.wallet  as destino,mw.status  "))
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
      ->select(DB::raw("'Compras en BTC' as tipo, mp.id as idtx, cc.dni, cc.name , mp.created, mc.code as moneda , amount as monto, tt.wallet , tt.reference, cc3.dni as iddestino,  cc3.name  as destino,mp.status"))
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
      ->select(DB::raw("'Compras en USD' as tipo, mp.id as idtx, cc.dni, cc.name , mp.created, mc.code as moneda , amount as monto, tt.wallet , tt.reference, cc3.dni as iddestino,  cc3.name  as destino,mp.status"))
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
      ->select(DB::raw("'Tx Recibidas' as tipo, mt.id as idtx , cc.dni, cc.name, mt.created, mc.code as moneda, round( mt.amount,8) as monto, '' as wallet, mt.comment as reference, cc2.dni as iddestino, cc2.name  as destino, mt.status"))
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
      ->select(DB::raw("'Tx Realizadas' as tipo, mt.id as idtx, cc.dni, cc.name, mt.created, mc.code as moneda, round(mt.amount,8) as monto, ''as wallet, mt.comment as reference, cc2.dni  as iddestino, cc2.name as destino, mt.status"))
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
      ->select(DB::raw("'Depostio BTC Interno' as tipo, md.id as idtx, cc.dni, cc.name, md.created , mc.code as moneda , round(md.amount,8) as monto, md.wallet, md.reference, cc2.dni as iddestino, cc2.name as destino , md.status"))
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
      ->select(DB::raw("'Depostio BTC Externo' as tipo, md.id as idtx, cc.dni, cc.name, md.modified as created, mc.code as moneda, md.amount as monto, md.wallet, md.reference, ' '  as iddestino, md.comment as destino, md.status"))
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
      ->select(DB::raw("'Depostio BTC Externo' as tipo, md.id as idtx, cc.dni, cc.name, md.modified as created, mc.code as moneda, md.amount as monto, md.wallet, md.reference, ' '  as iddestino, ' ' as destino, md.status"))
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
      ->select(DB::raw("'Depostio USD' as tipo, md.id as idtx, cc.dni, cc.name, md.modified as created, mc.code as moneda, md.amount as monto, ' ' as wallet, ' ' as reference, ' '  as iddestino, comment as destino, md.status"))
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
      ->select(DB::raw("'Pagos Recibidos' as tipo, mp.id  as idtx, cc3.dni as dni, cc3.name, mp.created, mc.code as moneda, round(tt.fiat_amount_to_commerce,8) as monto, tt.wallet , tt.reference , cc.dni as iddestino, cc.name as destino,  mp.status"))
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

    $data['csv'] = "csv/aml.search.{$date_from}.{$date_to}_at_" . date('Y-m-d H.i.s') . '.csv';
    file_put_contents($data['csv'], $csv);

    return view('aml',  $data);
  }
}
