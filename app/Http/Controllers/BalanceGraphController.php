<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class BalanceGraphController extends Controller
{
    function search(Request $request, $dni = null)
    {
        $dni = $dni ?? $request->input('dni');
        if (empty($dni)) {
            return view('balancegraph');
        }

        $data = [];
        $data['$dni'] = $dni;

        $query = "SELECT
            fecha,
            IF(moneda_id = 25, total, 0) USD,
            IF(moneda_id = 1, total, 0) BTC,
            IF(moneda_id = 1,
            round(total * 50000.00, 2),
            total) aprox_usd
        FROM
            saldos.totales t
        WHERE
            t.dni = '{$dni}'
        GROUP BY
            fecha, moneda_id 
        ORDER BY
            fecha,
            moneda_id DESC    
        ";

        $results = DB::connection('chivo-saldos')->select($query);

        $data['totales'] = $results;
        $data['dates'] = json_encode(array_column($results, 'fecha'), JSON_UNESCAPED_SLASHES );
        $data['usd'] = json_encode(array_column($results, 'USD'), JSON_UNESCAPED_SLASHES );
        $data['btc'] = json_encode(array_column($results, 'BTC'), JSON_UNESCAPED_SLASHES );
        $data['aprox_usd'] = json_encode(array_column($results, 'aprox_usd'), JSON_UNESCAPED_SLASHES );

        return view('balancegraph', $data);
    }
}
