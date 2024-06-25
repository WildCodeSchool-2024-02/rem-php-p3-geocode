<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class IpService
{
    public function __construct()
    {
    }

    public function getIp(Request $request): string
    {
        return $request->getClientIp();
    }
}
