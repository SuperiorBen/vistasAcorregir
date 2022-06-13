<?php

namespace App\Http\Controllers;

use App\Helpers\CsvHelper;
use Illuminate\Support\Facades\DB;
use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpParser\Node\Expr\Array_;

class BitgoController extends Controller
{
    function pendingApprovals(Request $request)
    {
        $token = "v2xacfd954c23813a1a439507e8834b645a71da012fc8cb7420ee1a16478371daf5";
        $url = "https://app.bitgo.com/api/v2/pendingApprovals";
        $response = Http::withToken($token)->get($url);

        if ($response->failed()) {
            return;
        }

        $pendingApprovals = $response->json()['pendingApprovals'];

        $filtered_array = [];
        $columns = [
            'id',
            'coin',
            'wallet',
            'state',
            'creator'
        ];
        
        foreach($pendingApprovals as $pendingApproval)
        {
            $filtered_array[] = array_intersect_key($pendingApproval, array_flip($columns)); 
        }

        $csv = CsvHelper::array2csv($filtered_array);

        $filename = 'pending.approvals.' . date('Y-m-d H.i.s');
        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'  => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}.csv",
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        return response($csv, 200, $headers);
    }
}
