<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class BalanceController extends Controller
{
    function search(Request $request, $dni = null)
    {
        $dni = $dni ?? $request->input('dni');
        if (empty($dni)) {
            return view('balance');
        }

        $data = [];
        $data['$dni'] = $dni;

        $query = "SELECT mc.name, SUM(balance.total) from(
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
        return view('balance', $data);
    }
}
