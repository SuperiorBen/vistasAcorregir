<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class TxController extends Controller
{
    function search(Request $request)
    {
        $dni = $request->input('dni');
        $request->request->get('my_param');

        $data = [];

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

        return view('tx',  $data);
    }
}
