<?php

namespace Couchcat\Console\Commands;

use Exception;
use Illuminate\Console\Command;

class CouchdbViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'couchdb:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $couch = resolve('Couchdb');
        try {
            $view_by_license = $couch->stale('ok')->limit(1)->getView('couchcat', 'by_licensed_from');
        } catch (Exception $e) {
            if ($e->getCode() == 404) {
                $this->info('Creating Licensee View');
                $view_licensed="function(doc) { if(doc.licensed_from) { emit(doc.licensed_from,null); } }";
                $view_review="function(doc) { if(doc.needs_review) { emit(doc._id,doc.bib_created); } }";
                $design_doc = new \stdClass();
                $design_doc->_id = '_design/couchcat';
                $design_doc->language = 'javascript';
                $design_doc->views =  [ 'by_licensed_from'=>  ['map' => $view_licensed ],
                                        'needs_review' => ['map' => $view_review] ];
                $couch->storeDoc($design_doc);
            }
        }
    }
}
