<?php

namespace Couchcat\Processors\Music;

use Illuminate\Support\Facades\Storage;

class Flac
{
    /**
     * Rename the flac files to be URL friendly
     *
     * @return void
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
}
