<?php

namespace App\Http\Controllers;

use App\Helpers\CsvHelper;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    function search(Request $request)
    {
        $wallet = $request->input('wallet');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        $data = [];
        $data['wallet']=$wallet;
        $query = DB::table('money_withdrawals', 'mw')
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

        $csv = CsvHelper::array2csv($results->toArray());
        
        $data['csv'] = "csv/wallet.search.{$wallet}.{$date_from}.{$date_to}_at_" . date('Y-m-d H.i.s') . '.csv';
        file_put_contents($data['csv'], $csv);

        $data['salidas']=$results;
        return view('wallet',  $data);
    }
}
