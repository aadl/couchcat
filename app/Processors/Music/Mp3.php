<?php

namespace App\Processors\Music;

use Illuminate\Support\Facades\Storage;
use Comodojo\Zip\Zip;
use FFMpeg;

class Mp3
{
    public function __construct($doc)
    {
        $this->doc = $doc;
        $this->couchid = $doc->_id;
        $this->ffmpeg = FFMpeg\FFMpeg::create();
    }

    /**
     * Rename the mp3 files to be URL friendly
     *
     * @return bool
     */
    public function fixMp3Filename($file)
    {
        $parsepath = pathinfo($file);
        $filename = $parsepath['filename'];
        $safe_filename = str_slug($filename, '-');
        if ($parsepath['extension'] !== 'mp3') {
            return false;
        }
        if ($filename !== $safe_filename) {
            $old_file = $file;
            $new_file = $parsepath['dirname'] . '/' . $safe_filename . '.flac';
            if (Storage::move($old_file, $new_file)) {
                return "Renamed $filename to $safe_filename";
            }
        } else {
            return false;
        }
    }

    public function createMp3Zip()
    {
        $directory = storage_path('app/music/'.$this->couchid .'/derivatives/tracks/');
        $zip_filename = $directory . $this->couchid . '.zip';
        $zip_exists = Storage::exists('music/'.$this->couchid.'/derivatives/'.$this->couchid . '.zip');

        abort_if($zip_exists, 500, 'MP3 zip file exists. Make sure this is correct.');

        $zip = Zip::create($zip_filename);
        $zip->setSkipped('HIDDEN');
        $files = Storage::allFiles('music/'.$this->couchid.'/derivatives/tracks/');
        $to_add = array();
        foreach($files as $file) {
            $parsepath = pathinfo($file);
            if($parsepath['extension'] == 'mp3') {
                $to_add[] = storage_path('app/'.$file);
            }
        }
        $zip->add($to_add);
        if ($zip->close()) {
            return "Created $zip_filename";
        }
    }
}
