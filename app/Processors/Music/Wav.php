<?php

namespace App\Processors\Music;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use FFMpeg;

class Wav
{
    public function __construct($couchid)
    {
        $this->couchid = $couchid;
        $this->ffmpeg = FFMpeg\FFMpeg::create();
    }
    /**
     * Rename the flac files to be URL friendly
     *
     * @return bool
     */
    public function fixWavFilename($file)
    {
        $parsepath = pathinfo($file);
        $filename = $parsepath['filename'];
        $safe_filename = Str::slug($filename, '-');
        if ($parsepath['extension'] !== 'wav') {
            return false;
        }
        if ($filename !== $safe_filename) {
            $old_file = $file;
            $new_file = $parsepath['dirname'] . '/' . $safe_filename . '.wav';
            if (Storage::move($old_file, $new_file)) {
                return "Renamed $filename to $safe_filename";
            }
        } else {
            return false;
        }
    }

    public function convertWavFlac($file)
    {
        $parsepath = pathinfo($file);
        if ($parsepath['extension'] !== 'wav') {
            return false;
        }
        $audio = $this->ffmpeg->open(storage_path('app/'.$file));
        $format = new FFMpeg\Format\Audio\Flac();
        $format
            ->setAudioChannels(2)
            ->setAudioKiloBitrate(1411);
        $flac_file = storage_path('app/music/'.$this->couchid.'/data/'.$parsepath['filename'].'.flac');
        $saved = $audio->save($format, $flac_file);
        if ($saved) {
            return "Converted $flac_file";
        }
    }
}
