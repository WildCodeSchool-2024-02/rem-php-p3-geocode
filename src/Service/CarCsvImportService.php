<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\Model;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

class CarCsvImportService
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    public function carImport(string $filename): void
    {
        $reader = Reader::createFromPath(
            __DIR__ . '/../../assets/imports/cars/' . $filename,
            'r'
        );
        $reader->setHeaderOffset(0);
        $results = $reader->getRecords();

        foreach ($results as $result) {
            //var_dump($result['geo_point_borne']);
            //die;
            $model = (new Model())
                ->setBrand($result['brand'])
                ->setModel($result['model'])
                ->setHybrid($result['is_hybrid'])
            ;

            $this->entityManager->persist($model);
        }
        $this->entityManager->flush();

        foreach ($results as $result) {
            $modelExist = $this->entityManager->getRepository(Model::class)
                ->findOneBy(['brand' => $result['brand'],
                    'model' => $result['model']]);
            if (!$modelExist == null) {
                $car = (new Car())
                    ->setModel($modelExist)
                    ->setColor1($result['color1'])
                    ->setColor2($result['color2'])
                ;
                $this->entityManager->persist($car);
                $this->entityManager->flush();
            }
        }
    }
}
