<?php

namespace App\Console\Commands\Processing;

use Illuminate\Console\Command;

class ProcessAlbum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:album {couchid : id of existing couch record}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process an album';

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
        $this->call('process:wav', [ 'couchid' => $couchid ]);
        $this->call('process:flac', [ 'couchid' => $couchid ]);
        $this->call('process:mp3', [ 'couchid' => $couchid ]);
        $this->call('process:mp3:metadata', [ 'couchid' => $couchid ]);
    }
}
