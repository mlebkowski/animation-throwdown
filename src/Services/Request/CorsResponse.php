<?php

namespace Nassau\CartoonBattle\Services\Request;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CorsResponse extends JsonResponse
{
    public function __construct($data, Request $request, $status = self::HTTP_OK, array $headers = [])
    {
        parent::__construct($data, $status, $headers + [
                'Access-Control-Allow-Credentials' => 'true',
                'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, PATCH, OPTIONS',
                'Access-Control-Allow-Headers' => 'Origin, Content-Type, Accept, Authorization',
                'Access-Control-Allow-Origin' => $request->headers->get('origin'),
                'Access-Control-Max-Age' => 3600,
                'Vary' => 'Origin',
            ]);
    }

}