<?php

namespace App\Http\Controllers\AP;

use App\Helpers\CsvHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  function dashboard(Request $request)
  {
    $data = [];
    $dni = $dni ?? $request->input('dni');
    $fechaHasta=$request->input('date_to');
   /* if (empty($dni)) {
        return view('ap/dashboard');
    }*/
    $data['tipo']=$request->input('dni');
   
 if (empty($fechaHasta)) {
    $fechaHasta=date("Y-m-d", strtotime(' - 1 days'));
    }

    $query = "select id_kpi, fecha, orden, kpi, valor, saldo_suav from kpi where fecha='$fechaHasta' order by orden ";

    $data["consulta"]=$query;
    $results = DB::connection('chivo-saldos')->select($query);
    $data['salidas']=$results;
    
    return view('ap/dashboard',  $data);
  }
}
