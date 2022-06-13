<?php

namespace App\Http\Controllers\AP;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class TxapController extends Controller
{
    function search(Request $request)
    {
        $dni = $request->input('dni');
        $request->request->get('my_param');

        $data = [];
        $query = "select atd.account_id as identificador_ordenante, up.user_profile_type as tipo_persona_ordenante, 
        up.full_name as nombres_ordenante, ui.identifier_type as tipo_documento_ordenante, 
        ui.identifier as documento_ordenante, uam2.account_id as identificador_destinatario, 
        up2.user_profile_type as tipo_persona_destinatario, up2.full_name as nombres_destinatario, 
        ui2.identifier_type as tipo_documento_destinatario, ui2.identifier as documento_destinatario,
        atd.transaction_id as id_transaccion, DATE_SUB(atd.time_stamp,INTERVAL 6 HOUR) as fecha_hora, 
        p.full_name as tipo_activo_virtual, atd.amount as monto_activo_virtual, atd.notional_amount as monto_dolares, 
        atd.description as concepto_transaccion
        from apex.user_identifiers ui 
        inner join apex.user_profiles up on ui.user_id = up.user_id 
        inner join apex.user_account_map uam on ui.user_id = uam.user_id 
        inner join apex.account_transaction_details atd on uam.account_id = atd.account_id 
        inner join apex.products p on atd.product_id = p.product_id 
        inner join apex.user_account_map uam2 on atd.counterparty_account_id = uam2.account_id
        inner join apex.user_profiles up2 on uam2.user_id = up2.user_id 
        inner join apex.user_identifiers ui2 on up2.user_id = ui2.user_id 
        WHERE ui.identifier = '$dni' and atd.transaction_reference_type = 4 
        and ui2.identifier_type <> 'phone' order by atd.time_stamp desc ";

        $data["consulta"]=$query;
        $results = DB::connection('chivo-ap')->select($query);
        $data['rtrbtcwalletwallet']=$results;


        $results = DB::table('money_withdrawals', 'mw')
            ->select(DB::raw('mw.id as idtx, cc.dni, cc."name" as nombre,  mw.created as fechamov, mw.amount as cantidad, mw.commission as comision, wallet, mw.status as estado'))
            ->join('commerces_commerce as cc', 'mw.commerce_id', '=', 'cc.id')
            ->where('cc.dni',  '=',  (string)$dni)
            ->get()
        ;

        $data['dni']=$dni;
        $data['salidasbtc']=$results;

        $results = DB::table('money_withdrawals', 'mw')
        ->select(DB::raw('mw.id as idtx, mw.created as fechamov, amount as cantidad, mw.status as estado, mba.number as cuenta, mb."name" as banco'))
        ->join('money_bankaccount as mba', 'mw.account_id', '=', 'mba.id')
        ->join('money_bank as mb', 'mba.bank_id', '=', 'mb.id')
        ->join('commerces_commerce as cc', 'mw.commerce_id', '=', 'cc.id')
        ->where('cc.dni',  '=',  (string)$dni)
        ->get()
    ;
    $data['salidasusd']=$results;


    $results = DB::table('money_deposit', 'md')
    ->select(DB::raw('md.id  as idtx, md.modified as fechamov, amount as cantidad, mc.code as moneda, md.status as estado, md."comment" as comentario'))
    ->join('money_currency as mc', 'md.currency_id', '=', 'mc.id')
    ->join('commerces_commerce as cc', 'md.commerce_id', '=', 'cc.id')
    ->where('cc.dni',  '=',  (string)$dni)
    ->get()
;
$data['depositos']=$results;


$results = DB::table('money_purchase', 'mp')
->select(DB::raw('mp.id  as idtx, mp.created as fechamov, amount as cantidad, mc.code as moneda, mp.status as estado, mp."comment" as comentario, cc3."name"  as comercio, cc3.dni as numcomercio'))
->join('money_currency as mc', 'mp.currency_id', '=', 'mc.id')
->join('commerces_commerce as cc', 'mp.commerce_id', '=', 'cc.id')
->join('transactions_transaction as tt', 'mp.transaction_id', '=', 'tt.id')
->join('commerces_commerce as cc3', 'tt.commerce_id', '=', 'cc3.id')
->where('cc.dni',  '=',  (string)$dni)
->get()
;
$data['compras']=$results;



$results = DB::table('money_transfer', 'mt')
->select(DB::raw(' mt.id  as idtx, mt.created as fechamov, amount as cantidad, mc.code as moneda, mt.status as estado, mt."comment" as comentario, mt.commerce_from_id as txorigen'))
->join('money_currency as mc', 'mt.currency_id', '=', 'mc.id')
->join('commerces_commerce as cc', 'mt.commerce_to_id', '=', 'cc.id')
->where('cc.dni',  '=',  (string)$dni)
->get()
;
$data['txrecibidas']=$results;



$results = DB::table('money_transfer', 'mt')
->select(DB::raw(' mt.id  as idtx, mt.created as fechamov, amount as cantidad, mc.code as moneda, mt.status as estado, mt."comment" as comentario, mt.commerce_to_id as txorigen'))
->join('money_currency as mc', 'mt.currency_id', '=', 'mc.id')
->join('commerces_commerce as cc', 'mt.commerce_from_id', '=', 'cc.id')
->where('cc.dni',  '=',  (string)$dni)
->get()
;
$data['txenviadas']=$results;

        return view('/ap/txap',  $data);
    }
}
