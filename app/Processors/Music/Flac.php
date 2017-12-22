<?php

namespace Couchcat\Processors\Music;

use Illuminate\Support\Facades\Storage;
use Comodojo\Zip\Zip;
use FFMpeg;

class Flac
{
    public function __construct($doc)
    {
        $this->doc = $doc;
        $this->couchid = $doc->_id;
        $this->ffmpeg = FFMpeg\FFMpeg::create();
    }
    /**
     * Rename the flac files to be URL friendly
     *
     * @return bool
     */
    public function fixFlacFilename($file)
    {
        $parsepath = pathinfo($file);
        $filename = $parsepath['filename'];
        $safe_filename = str_slug($filename, '-');
        if ($parsepath['extension'] !== 'flac') {
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

    public function createFlacZip()
    {
        $flac_location = storage_path('app/music/'.$this->couchid .'/data');
        $zip_location = storage_path('app/music/'.$this->couchid .'/derivatives/');
        $zip_filename = $zip_location . $this->couchid . '-flac.zip';
        $zip_exists = Storage::exists('music/'.$this->couchid.'/derivatives/'.$this->couchid . '-flac.zip');

        abort_if($zip_exists, 500, 'FLAC zip file exists. Make sure this is correct.');

        $zip = Zip::create($zip_filename);
        $zip->setSkipped('HIDDEN');
        $zip->add($flac_location, true);
        if ($zip->close()) {
            return "Created $zip_filename";
        }
    }

    public function convertFlacMp3($file)
    {
        $parsepath = pathinfo($file);
        if ($parsepath['extension'] !== 'flac') {
            return false;
        }
        $audio = $this->ffmpeg->open(storage_path('app/'.$file));
        $format = new FFMpeg\Format\Audio\Mp3();
        $format
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(320);
        $mp3_file = storage_path('app/music/'.$this->couchid.'/derivatives/'.$parsepath['filename'].'.mp3');
        $saved = $audio->save($format, $mp3_file);
        if ($saved) {
            return "Converted $mp3_file";
        }
    }
}
