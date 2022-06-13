<?php

namespace App\Http\Controllers\AP;

use App\Helpers\CsvHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class UsuariosController extends Controller
{
  function usuarios(Request $request)
  {
    $query = "select count(distinct(user_id)) as total from audit_log where endpoint_name ='GetUserProfile'";

    $results = DB::connection('chivo-ap')->select($query);
    $data = [ 'total' => $results[0]->total];
    return view('ap/usuarios',  $data);
  }
}
