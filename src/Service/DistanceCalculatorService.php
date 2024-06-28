<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class DistanceCalculatorService
{
    public function calculateDistance(int $battery): float
    {
        $distance = $battery * 4.67;
        return $distance;
    }
}
