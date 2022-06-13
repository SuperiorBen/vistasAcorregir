<?php

namespace App\Http\Controllers\AP;

use App\Http\Controllers\Controller;
use App\Helpers\CsvHelper;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class WalletapController extends Controller
{
    function search(Request $request)
    {
        $wallet = $request->input('wallet');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        if (empty($wallet)) {
            return view('ap/walletap',  []);
        }

        $data = [];
        
        ini_set('memory_limit', '512M');
        
        $id_atd_data = DB::connection('sphinx')
                ->table('idx_ap_atd')
                ->whereRaw("MATCH('{$wallet}')")
                ->limit(1000)
                ->get();

        $id_atd = array_column($id_atd_data->toArray(),'id');

        $query = "select u.account_id, u.user_id, account_name, username as tel, identifier, identifier_type, time_stamp as fechamov, account_name, verification_level, frozen,  locked, transaction_id, notional_amount, tx_status  from users u 
      inner JOIN accounts a  ON u.account_id = a.account_id
      inner join account_transaction_details atd on a.account_id =atd.account_id 
      inner JOIN user_identifiers ui ON ui.user_id = u.user_id
      WHERE  identifier_type in('dui','nit', 'pa', 'fraud-dui')
      AND atd.transaction_id IN (".implode(',', $id_atd).")";

        $results = DB::connection('chivo-ap')->select($query);
        $data['wallet']=$wallet;
       /* $query = DB::table('money_withdrawals', 'mw')
            ->select(DB::raw("mw.id as idtx, cc.dni, cc.name as nombre, number as telefono, TO_CHAR(mw.created, 'HH24:MI:SS DD/MM/YYYY') as fechamov, mc.symbol, mw.amount as cantidad, mw.commission as comision, mw.status as estado"))
            ->join('money_currency as mc', 'mw.currency_id', '=', 'mc.id')
            ->join('commerces_commerce as cc', 'mw.commerce_id', '=', 'cc.id')
            ->join('locations_commercephone as lcp', 'cc.id', '=', 'lcp.commerce_id')
            ->join('locations_phone as lp', 'lcp.phone_ptr_id', '=', 'lp.id')
            ->limit(5000);

        if (!empty($wallet)) {
            $query->where('mw.wallet',  '=',  (string)$wallet);
        }

        if (!empty($date_from)) {
            $query->where('mw.created', '>=', $date_from);
        }

        if (!empty($date_to)) {
            $query->where('mw.created', '<=', $date_to);
        }

        if (empty($wallet) && empty($date_from) && empty($date_to)) {
            $query->orderBy('mw.created', 'desc');
        } else {
            $query->orderBy('mw.created', 'desc');
        }

        //$query->dd();
        $results = $query->get();
*/
        $csv = CsvHelper::array2csv($results);
        
       $data['csv'] = "csv/walletap.search.{$wallet}.{$date_from}.{$date_to}_at_" . date('Y-m-d H.i.s') . '.csv';

        file_put_contents($data['csv'], $csv);

        $data['salidas']=$results;
        return view('ap/walletap',  $data);
    }
}
