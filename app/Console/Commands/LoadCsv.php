<?php

namespace Couchcat\Console\Commands;

use Illuminate\Console\Command;
use League\Csv\Reader;

class LoadCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:csv {csvfile : CSV Filename}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load CSV file of metadata into CouchDB';

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
        $csvfile = $this->argument('csvfile');
        
        $publisher = $this->ask('Publisher?');
        $mat_code = $this->ask('Mat Code?');
        $licensee = $this->ask('Licensee Stub?');
        $csv = Reader::createFromPath(storage_path($csvfile), 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $required_fields = array('id', 'title');
        $fields = $csv->getHeader();
        if (count(array_intersect($fields, $required_fields)) !== count($required_fields)) {
            abort(500, 'CSV is missing a required field. They are: '. implode($required_fields, ', '));
        }

        foreach ($records as $record) {
            $doc = new \stdClass();
            $doc->_id = $record['id'];
            $doc->title = $record['title'];
            // Music has artist
            if ($mat_code == 'z') {
                $doc->artist = $record['artist'] ?? null;
            } else {
                $doc->author = $record['author'] ?? null;
                $additional_authors = $record['additional_authors'] ?? null;
                if ($additional_authors) {
                    $doc->aadl_author = array_map('trim', explode(',', $additional_authors));
                }
                $isbns = $record['isbns'] ?? null;
                if ($isbns) {
                    $doc->stdnum = array_map('trim', explode(',', $isbns));
                }
            }
            $doc->overview = $record['description'] ?? null;
            $doc->upc = $record['upc'] ?? null;
            $doc->pub_year = $record['pub_year'] ?? null;
            $doc->pub_info = $record['publisher'] ?? $publisher;
            $doc->licensed_from = $licensee;
            // Get rid of the null values
            // Need to use function so it doesn't wipe booleans.
            $cleaned_doc = (object) array_filter((array) $doc, function ($val) {
                return !is_null($val);
            });
            $this->info(print_r($cleaned_doc));
            try {
                $this->couch->storeDoc($cleaned_doc);
            } catch (Exception $e) {
                $this->error("Saving record failed : " . $e->getMessage());
            }
            break;
        }
    }
}
