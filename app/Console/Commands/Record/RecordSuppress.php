<?php

namespace App\Console\Commands\Record;

use Illuminate\Console\Command;

class RecordSuppress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'record:suppress {record_id : CouchDB ID of Record to Suppress}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently suppress a catalog record';

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
        $record_id = $this->argument('record_id');

        try {
            $record = $this->couch->getDoc($record_id);
        } catch (Exception $e) {
            abort("Getting record failed : " . $e->getMessage());
        }

        $record->active = 0;
        $record->flags = $record->flags ?? new \stdClass;
        $record->flags->protected = 1;

        try {
            $this->couch->storeDoc($record);
        } catch (Exception $e) {
            $this->error("Getting record failed : " . $e->getMessage());
        }


    }
}
