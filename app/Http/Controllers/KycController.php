<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class KycController extends Controller
{
    function search(Request $request, $dni = null)
    {

        $dni = $dni ?? $request->input('dni');
        if (empty($dni))
        {
            return view('kyc');
        }

        $data = [];
        $data['$dni'] = $dni;
        $results = DB::table('commerces_commerce', 'cc')
            ->select(DB::raw('cc.dni, cc.name, cc.created, dc.face, df.upload'))
            ->join('documents_commercefile as dc', 'cc.id', '=', 'dc.commerce_id')
            ->join('documents_file as df', 'df.id', '=', 'dc.file_ptr_id')
            ->where('cc.dni', '=',  (string)$dni)
            ->get()
        ;

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION')
        ]);

        $name = '';
        $img_urls = [];
        foreach($results as $result)
        {
            $data['name'] = $result->name;
            $data['creacion'] = $result->created;
            try {
                $cmd = $s3->getCommand('GetObject', [
                    'Bucket' => env('AWS_BUCKET'),
                    'Key'    => $result->upload
                ]);
    
                $request = $s3->createPresignedRequest($cmd, '+5 minutes');
                $presignedUrl = (string) $request->getUri();
    
                $img_urls[] = $presignedUrl;
                
            } catch (S3Exception $e) {
                echo "There was an error with the file.\n" . $e->getMessage();
            }
            
        }

        $data['imgs'] = $img_urls;


        return view('kyc', $data);
    }
}
