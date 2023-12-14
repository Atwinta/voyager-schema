<?php

namespace Atwinta\Voyager\Services\Abstracts;


interface VoyagerInterface
{
    /**
     * @return mixed
     */
    public function schemaGenerate();

    /**
     * @return mixed
     */
    public function menuGenerate();

    /**
     * @return void
     */
    public function settingsGenerate(): void;
}