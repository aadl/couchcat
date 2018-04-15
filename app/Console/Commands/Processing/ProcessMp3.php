<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Processors\Music\Mp3;
use FFMpeg;

class ProcessMp3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:mp3 {couchid : id of existing couch record}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a MP3 album';

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
        $mp3 = new Mp3($doc);

        $files = Storage::allFiles('music/'.$couchid .'/derivatives');
        foreach ($files as $file) {
            if ($renamed = $mp3->fixMp3Filename($file)) {
                $this->info($renamed);
            }
        }
        $this->info($mp3->createMp3Zip());
    }
}
