<?php

namespace Spork\Greenhouse\Actions;

use Illuminate\Http\Response;

interface ActionInterface 
{
    public function getName(): string;
    public function getUrl(): string;
}