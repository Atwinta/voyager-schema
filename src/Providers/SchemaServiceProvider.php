<?php

namespace Atwinta\Voyager\Providers;

use Atwinta\Voyager\Console\Commands\VoyagerSchema;
use Atwinta\Voyager\Services\Abstracts\VoyagerInterface;
use Atwinta\Voyager\Services\VoyagerService;
use Illuminate\Support\ServiceProvider;

class SchemaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(VoyagerInterface::class, function () {
            return new VoyagerService(
                config("voyager-schema")
            );
        });

        $this->commands([
            VoyagerSchema::class
        ]);
    }

    public function boot()
    {
        $this->publishes([__DIR__ . "/../Schema/Tables" => app_path("Schema/Tables")]);
        $this->publishes([__DIR__ . '/../../config' => config_path() . '/']);
    }
}
