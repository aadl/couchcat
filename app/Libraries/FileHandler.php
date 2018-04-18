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
        $response = $guzzle->request('GET', null, ['sink' => $cover_file]);
    }

    public function uploadFile($file, $bucket, $path = NULL)
    {
        $s3 = AWS::createClient('s3');
        $key = (isset($path) ? $path . $file : $file);
        $key = str_replace('app/', '', $key);
        return $s3->putObject([
            'Bucket'     => $bucket,
            'Key'        => $key,
            'SourceFile' => storage_path($file),
        ]);
    }
}
