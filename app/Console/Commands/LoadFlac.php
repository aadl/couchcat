<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class LoadFlac extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:flac {dir : name of directory}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load Flac Album into CouchDb';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = $this->argument('dir');
        $couch = resolve('Couchdb');
        
        $doc = new \stdClass();
        $doc->_id = str_slug($directory, '-');
        $doc->qsrcode = substr($directory, 0, 7);
        
        $withoutqsr = trim(str_after($directory, $doc->qsrcode), ' ()');
        $artistalbum = explode(' - ', $withoutqsr);
        $doc->artist = $artistalbum[0];
        $doc->title = $artistalbum[1];

        $doc->licenced_from = 'qsr';

        $files = Storage::allFiles('music/'.$directory .'/flac');
        foreach ($files as $file) {
            $ffprobe = \FFMpeg\FFProbe::create();
            $parsepath = pathinfo($file);
            $filename = $parsepath['filename'];
            if (!starts_with($filename, '.') && $parsepath['extension'] == 'flac') {
                $length = $ffprobe->format(storage_path('app/'.$file))->get('duration');
                $tracknum = (int)substr($filename, 0, 2);
                $trackname = substr($filename, 3);
                $tracks[$tracknum]['title'] = $trackname;
                $tracks[$tracknum]['length'] = (string)round($length);
            }
        }
        $doc->tracks = $tracks;
        $doc->needs_review = 1;
        $doc->mat_code = 'z';
        $doc->bib_created = date('Y-m-d');
        $doc->active = 0;
        sscanf(crc32($doc->_id), "%u", $crc_id);
        $doc->sphinx_id = (string)$crc_id;
        $this->info(print_r($doc));
        if ($this->confirm('Do you wish to save?')) {
            $couch->storeDoc($doc);
        }
    }
}
