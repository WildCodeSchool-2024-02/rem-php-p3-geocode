<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\Model;
use App\Entity\Stations;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use League\Csv\Statement;

class StationCsvImportService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function stationImport(string $filename): void
    {
        $reader = Reader::createFromPath(
            __DIR__ . '/../../assets/imports/stations/' . $filename,
            'r'
        );
        $reader->setDelimiter(';');
        $reader->setHeaderOffset(0);
        $stmt = Statement::create()->limit(1000)->offset(0);
        $results = $stmt->process($reader);

        foreach ($results as $result) {
            //var_dump($result['geo_point_borne']);
            //die;
            $station = (new Stations())
                ->setIdStation($result['id_station'])
                ->setStationName($result['n_station'])
                ->setStationAddress($result['ad_station'])
                ->setInseeCode($result['code_insee'])
                ->setLongitude($result['xlongitude'])
                ->setLatitude($result['ylatitude'])
                ->setMaxPower($result['puiss_max'])
                ->setFree(false)
                //->setGeopoint($result['geo_point_borne']);
            ;

            $this->entityManager->persist($station);

            $this->entityManager->flush();
        }
    }
}
