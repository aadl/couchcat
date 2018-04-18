<?php

namespace App\Console\Commands;

use App\Libraries\Syndetics;
use App\Libraries\FileHandler;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;

class CacheCovers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:covers { record_ids* : Array of record ids to cache covers for }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache a cover for a bib';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->covercache = new FileHandler;
        $this->syndetics = new Syndetics('anarp');
        $this->guzzle = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $record_ids = $this->argument('record_ids');
        foreach ($record_ids as $record_id) {
            try {
                $cover_check = $this->guzzle->request('GET', 'https://aadl.org/files/covers/'.$record_id.'.jpg');
            } catch (RequestException $e) {
                $response = $this->guzzle->request('GET', 'https://api.aadl.org/record/'.$record_id);
                $record = json_decode($response->getBody());
                if (isset($record->stdnum)) {
                    if (is_array($record->stdnum)) {
                        $isbn = $record->stdnum[0];
                    } else {
                        $isbn = $record->stdnum;
                    }
                    if ($coverurl = $this->syndetics->getIsbn($isbn)->getCoverUrl()) {
                        $this->covercache->saveCover($coverurl, $record_id);
                        $this->covercache->uploadFile($record_id.'.jpg', 'covers');
                        $this->call('aws:invalidate', ['paths' => ['/cover/200/'.$record_id.'.jpg']]);
                    }
                } elseif (isset($record->upc)) {
                    if (is_array($record->upc)) {
                        $upc = $record->upc[0];
                    } else {
                        $upc = $record->upc;
                    }
                    if ($coverurl = $this->syndetics->getUpc($upc)->getCoverUrl()) {
                        $this->covercache->saveCover($coverurl, $record_id);
                        $this->covercache->uploadFile($record_id.'.jpg', 'covers');
                        $this->call('aws:invalidate', ['paths' => ['/cover/200/'.$record_id.'.jpg']]);
                    }
                }
            }
        }
    }
}
