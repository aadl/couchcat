<?php

namespace Couchcat\Console\Commands;

use Illuminate\Console\Command;

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
