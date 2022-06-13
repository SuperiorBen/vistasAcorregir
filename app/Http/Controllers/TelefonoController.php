<?php

namespace App\Http\Controllers;

use App\Helpers\CsvHelper;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class TelefonoController extends Controller
{
    function search(Request $request)
    {
        $dni = $request->input('dni');
        $date_from = $request->input('date_from');
        $date_to = $request->input('date_to');

        $data = [];
        $data['dni']=$dni;
        $query = DB::table('locations_commercephone', 'lc')
            ->select(DB::raw("cc.id as idtx , cc.created  as fechacreacion, dni, number as telefono, name as nombre"))
            ->join('locations_phone as lp', 'lc.phone_ptr_id', '=', 'lp.id')
            ->join('commerces_commerce as cc', 'lc.commerce_id', '=', 'cc.id')           
            ->limit(5000);

        if (!empty($dni)) {
            $dnis=[];
           // $dnis=explode(',', $dni);
           
           $dnis=preg_split("/\r\n|\n|\r/", $dni);

            //$query->whereIn('cc.dni',  [$telefono]);
            $query->whereIn('cc.dni', $dnis);
        }

        if (!empty($date_from)) {
            $query->where('cc.created', '>=', $date_from);
        }

        if (!empty($date_to)) {
            $query->where('cc.created', '<=', $date_to);
        }

        if (empty($telefono) && empty($date_from) && empty($date_to)) {
            $query->orderBy('cc.created', 'desc');
        } else {
            $query->orderBy('cc.created', 'desc');
        }

        //$query->dd();
        $results = $query->get();

        $csv = CsvHelper::array2csv($results->toArray());
        $dni=substr($dni, 1, 30);
        $data['csv'] = "csv/telefono.search.{$date_from}.{$date_to}_at_" . date('Y-m-d H.i.s') . '.csv';
        file_put_contents($data['csv'], $csv);

        $data['salidas']=$results;
        return view('telefono',  $data);
    }
}
