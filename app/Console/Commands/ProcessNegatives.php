<?php

namespace Couchcat\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ProcessNegatives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:negatives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process OldNews Negatives';

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
        $oldnews_mount = config('filesystems.disks.oldnews.root');
        $tif_for_conversion = config('filesystems.disks.oldnews.negatives_to_convert');
        $completed_tifs = config('filesystems.disks.oldnews.negatives_completed_tifs');
        $jpg_folder = config('filesystems.disks.oldnews.negatives_jpg_completed');

        if (!File::exists($tif_for_conversion)) {
            $this->error('Oldnews Mount Is Missing');
        }

        $files = File::files($tif_for_conversion);
        foreach ($files as $file) {
            if (preg_match('(tif|tiff)', File::extension($file)) === 1) {
                if (File::exists($completed_tifs . File::basename($file))) {
                    $this->error(File::basename($file) . ' has already been processed');
                    break;
                }
                
                Log::info('Processing Negative', ['filename' => File::basename($file)]);

                $image = Image::make($file);
                $height = $image->height();
                $width = $image->width();

                // Archives wants the longest side changed to 4000px
                if ($width > $height) {
                    $image->resize(4000, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                } else {
                    $image->resize(null, 4000, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                }

                $image->save($jpg_folder . File::name($file) . '.jpg');

                File::move($file, $completed_tifs.File::basename($file));
            }
        }
    }
}
