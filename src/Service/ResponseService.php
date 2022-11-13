<?php

namespace App\Service;

abstract class ResponseService
{
    public function __construct(
        protected SerialiseService $serialiseService
    ) { }
}
