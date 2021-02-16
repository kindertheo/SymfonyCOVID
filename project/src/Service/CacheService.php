<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;

class CacheService{

    public function response(Response $response): Response
    {

        // cache publicly for 3600 seconds
        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;

    }
}