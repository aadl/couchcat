<?php

namespace Couchcat\Processors\Music;

use Illuminate\Support\Facades\Storage;
use Comodojo\Zip\Zip;

class Flac
{
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
            return Storage::move($old_file, $new_file);
        } else {
            return false;
        }
    }

    public function createFlacZip($couchid)
    {
        $flac_location = storage_path('app/music/'.$couchid .'/data');
        $zip_location = storage_path('app/music/'.$couchid .'/derivatives/');
        $zip_filename = $zip_location . $couchid . '-flac.zip';
        $zip = Zip::create($zip_filename);
        $zip->setSkipped('HIDDEN');
        $zip->add($flac_location, true);
        return $zip->close();
    }
}
