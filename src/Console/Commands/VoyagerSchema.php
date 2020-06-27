<?php

namespace Atwinta\Voyager\Console\Commands;

use Atwinta\Voyager\Services\Abstracts\VoyagerInterface;
use Illuminate\Console\Command;

class VoyagerSchema extends Command
{
    /**
     * @var string
     */
    protected $signature = "voyager:schema";

    /**
     * @var string
     */
    protected $description = "generate voyager schema";

    /**
     * @param VoyagerInterface $service
     */
    public function handle(VoyagerInterface $service)
    {
        $this->info("Import schema");
        $service->schemaGenerate();
        $this->info("Import menu");
        $service->menuGenerate();
    }
}