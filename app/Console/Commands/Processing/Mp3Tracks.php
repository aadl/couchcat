<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Processors\Music\Mp3;
use FFMpeg;

class Mp3Tracks extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mp3:process-tracks {couchid : id of existing couch record}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load track file data into CouchDB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->couch = resolve('Couchdb');
        $this->ffmpeg = FFMpeg\FFProbe::create();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $couchid = $this->argument('couchid');
        $doc = $this->couch->getDoc($couchid);
        $files = Storage::allFiles('music/'.$couchid .'/derivatives');
        foreach ($files as $file) {
            $parsepath = pathinfo($file);
            if($parsepath['extension'] == 'mp3') {
                $audio = $this->ffmpeg->format(storage_path('app/'.$file));
                $track_num = (int) substr($parsepath['filename'], 0, 2);
                if($doc->tracks->$track_num) {
                    $doc->tracks->$track_num->filename = $parsepath['filename'] . '.mp3';
                    $doc->tracks->$track_num->size = (int) round($audio->get('size'));
                    $doc->tracks->$track_num->length = (int) round($audio->get('duration'));
                }
            }
        }
        $this->couch->storeDoc($doc);
    }
}
