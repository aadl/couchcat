<?php

namespace Couchcat\Console\Commands;

use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

class LoadMagnatune extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:magnatune {--d|download : force download of magnatune album list}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load New Magnatune Records';

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
        $force_download = $this->option('download');
        $storage_path = config('filesystems.disks.local.root');
        $download_username = config('music.magnatune.username');
        $download_password = config('music.magnatune.password');

        $couch = resolve('Couchdb');

        $client = new Client(['base_uri' => 'http://he3.magnatune.com/info/']);
        $response = $client->request('HEAD', 'album_info_xml.gz');
        $last_modified = new Carbon($response->getHeader('Last-Modified')[0]);

        // Only download the file if it has been modified recently or forced
        if ($last_modified->gte(Carbon::yesterday()) || $force_download) {
            $client->request('GET', 'album_info_xml.gz', ['sink' => $storage_path.'/album_info_xml.gz']);
        }

        // Quite a few of the simplexml are type cast to be sure what gets stored in couchdb
        $album_xml = simplexml_load_file('compress.zlib://'.$storage_path.'/album_info_xml.gz');
        $albums = $album_xml->xpath('//AllAlbums/Album');

        foreach ($albums as $album) {
            $id = (string)$album->albumsku;
            $album_title = (string)$album->albumname;
            $artist = (string)$album->artist;
            try {
                $doc = $couch->getDoc($id);
            } catch (Exception $e) {
                if ($e->getCode() == 404) {
                    $this->info('Loading Missing Album ' . $id);
                    // try {
                    //     $download_url = "http://download.magnatune.com/music/$artist/$album_title/$id-flac.zip";
                    //     $client->request('GET', $download_url, [
                    //         'sink' => $storage_path."/$id-flac.zip",
                    //         'auth' => [ $download_username, $download_password ],
                    //     ]);
                    // } catch (RequestException $e) {
                    //     $this->error("Problem downloading album $id");
                    // }
                    // Create CouchDB Record
                    $doc = new \stdClass();
                    $doc->_id = $id;
                    $doc->magnatune_id = $id;
                    $doc->title = $album_title;
                    $doc->artist = $artist;
                    $doc->pub_year = (string)$album->year;
                    if(strlen((string)$album->album_notes) > 0) {
                      $doc->notes = (string)$album->album_notes;
                    }
                    $doc->release_date = (string)$album->launchdate;
                    $doc->cover_url = (string)$album->cover_small;
                    foreach($album->Track as $track) {
                      $tracknum = (int)$track->tracknum;
                      $tracks[$tracknum]['title'] = (string)$track->trackname;
                      $tracks[$tracknum]['length'] = (string)$track->seconds;
                      $doc->magnatune_url = (string)$track->home;
                      $doc->license = (string)$track->license;
                      $genres = (string)$track->magnatunegenres;
                    }
                    $doc->tracks = $tracks;
                    $genres = explode(',',trim($genres));
                    $add_genre = array();
                    foreach($genres as $genre) {
                      if($genre == 'Electronica')
                        $add_genre[] = 'Electronic';
                      if($genre == 'Ambient')
                        $add_genre[] = 'Electronic';
                      if($genre == 'Hip Hop')
                        $add_genre[] = 'Rap';
                      if($genre == 'Alt Rock')
                        $add_genre[] = 'Rock';
                      if($genre == 'Electro Rock')
                        $add_genre[] = 'Electronic';
                      if($genre == 'Hard Rock')
                        $add_genre[] = 'Rock';
                    }
                    if($add_genre){
                      $genres = array_merge($genres,$add_genre);
                    }
                    $doc->genres = $genres;
                    $doc->bib_created = date('Y-m-d');
                    $doc->active = 0;
                    try {
                        $couch->storeDoc($doc);
                        unset($doc);
                        unset($genre);
                        unset($add_genre);
                    } catch (Exception $e) {
                        $this->error($e->getMessage()." (".$e->getCode().")");
                    }
                } else {
                    $this->error('Something is wrong with the CouchDB server');
                    break;
                }
            }
        }
    }
}
