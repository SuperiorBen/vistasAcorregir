<?php

namespace App\Http\Controllers\AP;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;

class KycController extends Controller
{
    function search(Request $request, $dni = null)
    {

        $dni = $dni ?? $request->input('dni');
        if (empty($dni)) {
            return view('ap/kyc');
        }

        $data = [];
        $data['$dni'] = $dni;


        //--- old
        $results = DB::table('commerces_commerce', 'cc')
            ->select(DB::raw('cc.dni, cc.name, cc.created, dc.face, df.upload'))
            ->join('documents_commercefile as dc', 'cc.id', '=', 'dc.commerce_id')
            ->join('documents_file as df', 'df.id', '=', 'dc.file_ptr_id')
            ->where('cc.dni', '=',  (string)$dni)
            ->get();

        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION')
        ]);

        $name = '';
        $img_urls = [];
        foreach ($results as $result) {
            $data['name_old'] = $result->name;
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

        $data['imgs_old'] = $img_urls;

        //---

        $query = "SELECT * FROM user_configs uc JOIN user_profiles up ON uc.user_id = up.user_id
        WHERE uc.user_id IN (
        SELECT user_id FROM user_identifiers ui WHERE identifier = '" . (string)$dni . "'
        )
        AND uc.config_id IN ('KYCImage-back', 'KYCImage-dui_image', 'KYCImage-front', 'KYCImage-liveness_image')
        ";

        $results = DB::connection('chivo-ap')->select($query);

        $s3 = new S3Client([
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID_AP'),
                'secret' => env('AWS_SECRET_ACCESS_KEY_AP'),
            ],
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION_AP')
        ]);

        $name = '';
        $img_urls = [];
        foreach ($results as $result) {
            $data['name'] = $result->full_name;
            try {
                $key = str_ireplace('https://chv-prod.s3.sa-east-1.amazonaws.com/', '', $result->config_value);

                $cmd = $s3->getCommand('GetObject', [
                    'Bucket' => env('AWS_BUCKET_AP'),
                    'Key'    => $key
                ]);


                $request = $s3->createPresignedRequest($cmd, '+5 minutes');
                $presignedUrl = (string) $request->getUri();

                $img_urls[] = $presignedUrl;
            } catch (S3Exception $e) {
                echo "There was an error with the file.\n" . $e->getMessage();
            }
        }

        $query = "SELECT full_name, avatar_image FROM user_profiles up
        WHERE up.user_id IN (
            SELECT user_id
            FROM user_identifiers ui
            WHERE identifier = '" . (string)$dni . "'
        )
        ";

        $results = DB::connection('chivo-ap')->select($query);
        if ($results[0]->avatar_image) {
            $data['name'] = $results[0]->full_name;
            try {
                $key = str_ireplace('https://chv-prod.s3.sa-east-1.amazonaws.com/', '', $results[0]->avatar_image);

                $cmd = $s3->getCommand('GetObject', [
                    'Bucket' => env('AWS_BUCKET_AP'),
                    'Key'    => $key
                ]);


                $request = $s3->createPresignedRequest($cmd, '+5 minutes');
                $presignedUrl = (string) $request->getUri();

                $img_urls[] = $presignedUrl;
            } catch (S3Exception $e) {
                echo "There was an error with the file.\n" . $e->getMessage();
            }
        }

        $data['imgs'] = $img_urls;


        return view('ap/kyc', $data);
    }
}
