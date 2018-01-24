<?php

namespace Couchcat\Libraries;

use AWS;
use GuzzleHttp\Client;
use IsoCodes\Isbn;

class CoverCache
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

    public function uploadCover($cover_file)
    {
        $s3 = AWS::createClient('s3');
        return $s3->putObject(array(
            'Bucket'     => 'covers',
            'Key'        => $cover_file,
            'SourceFile' => storage_path($cover_file),
        ));
    }
}
