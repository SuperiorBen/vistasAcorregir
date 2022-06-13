<?php

namespace App\Http\Controllers\AP;

use App\Helpers\CsvHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class LimitController extends Controller
{
  function limit(Request $request)
  {
    $data = [];
    $request->request->get('my_param');

    $tipo=$request->input('tipo');
    $nivel=$request->input('nivel');
    if (($nivel==0) ) {
        return view('ap/limit');
    }
    $data['tipo']=$request->input('tipo');
    $data['nivel']=$request->input('nivel');
   

    $query = "select id_usuario_limite, verifcation_level, type, account_id, account_name, round(total) as total from usuarios_limites where type='$tipo' and  verifcation_level=$nivel ";

    $data["consulta"]=$query;
    $results = DB::connection('chivo-saldos')->select($query);
    $data['salidas']=$results;
    
    return view('ap/limit',  $data);
  }
}
