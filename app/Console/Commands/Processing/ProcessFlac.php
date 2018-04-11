<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Processors\Music\Flac;
use FFMpeg;

class ProcessFlac extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:flac {couchid : id of existing couch record}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a FLAC album';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->couch = resolve('Couchdb');
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
        $flac = new Flac($doc);

        Storage::makeDirectory('music/'.$couchid .'/derivatives');
        $this->info($flac->createFlacZip());

        $files = Storage::allFiles('music/'.$couchid .'/data');
        foreach ($files as $file) {
            if ($renamed = $flac->fixFlacFilename($file)) {
                $this->info($renamed);
            }
            if ($converted = $flac->convertFlacMp3($file)) {
                $this->info($converted);
            }
        }
    }
}
