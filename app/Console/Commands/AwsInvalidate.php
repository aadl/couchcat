<?php

namespace Couchcat\Console\Commands;

use AWS;
use Illuminate\Console\Command;

class AwsInvalidate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aws:invalidate { paths* : Array of paths to invalidate }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invalidate Cloudfront Paths';

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
        $invalidation_paths = $this->argument('paths');

        $cloudfront = AWS::createClient('cloudfront');
        $cloudfront->createInvalidation([
            'DistributionId' => env('AWS_CLOUDFRONT_DISTRIBUTION'),
            'InvalidationBatch' => [
                'CallerReference' => uniqid('aws_'),
                'Paths' => [
                    'Items' => $invalidation_paths,
                    'Quantity' => count($invalidation_paths),
                ],
            ],
        ]);
    }
}
