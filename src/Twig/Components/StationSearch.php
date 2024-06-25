<?php

namespace App\Twig\Components;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use App\Repository\StationsRepository;

#[AsLiveComponent]
final class StationSearch
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $query = '';

    public function __construct(private StationsRepository $stationsRepository)
    {
    }

    public function getStations(): array
    {
        return $this->stationsRepository->findLikeInsee($this->query);
    }
}
