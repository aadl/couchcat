<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use PHPOnCouch\Couch;
use PHPOnCouch\CouchClient;
use Illuminate\Support\ServiceProvider;

class CouchdbServiceProvider extends ServiceProvider  implements DeferrableProvider
{

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Couchdb', function ($app) {
            $server = config('database.couchdb.uri');
            $database = config('database.couchdb.database');
            return new CouchClient($server, $database);
        });
    }

    public function provides()
    {
        return ['Couchdb'];
    }
}
