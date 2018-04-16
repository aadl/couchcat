<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Processors\Music\Wav;
use FFMpeg;

class ProcessWav extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:wav {couchid : id of existing couch record}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a WAV album';

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
        $couchid = $this->argument('couchid');
        $wav = new Wav($couchid);

        Storage::makeDirectory('music/'.$couchid .'/data');
        $files = Storage::files('music/'.$couchid);
        foreach ($files as $file) {
            if ($renamed = $wav->fixWavFilename($file)) {
                $this->info($renamed);
            }
        }
        $renamed_files = Storage::files('music/'.$couchid);
        foreach ($renamed_files as $file) {
            if ($converted = $wav->convertWavFlac($file)) {
                $this->info($converted);
            }
        }
    }
}
