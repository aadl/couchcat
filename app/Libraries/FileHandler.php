<?php

namespace App\Libraries;

use AWS;
use GuzzleHttp\Client;
use IsoCodes\Isbn;

class FileHandler
{
    public function __construct()
    {
        //
    }

    public function saveCover($url, $record_id)
    {
        $cover_file = storage_path($record_id . '.jpg');
        $guzzle = new Client(['base_uri' => $url]);
        $response = $guzzle->request('GET', null, ['sink' => $cover_file, 'timeout' => 300]);
    }

    public function uploadFile($file, $bucket, $path = null)
    {
        $s3 = AWS::createClient('s3');
        $split = explode('/', $file);
        $key = (isset($path) ? $path . end($split) : $file);
        $key = str_replace('app/', '', $key);
        return $s3->putObject([
            'Bucket'     => $bucket,
            'Key'        => $key,
            'SourceFile' => storage_path($file),
        ]);
    }
}
