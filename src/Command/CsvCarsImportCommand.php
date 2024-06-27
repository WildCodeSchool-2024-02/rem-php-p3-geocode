<?php

namespace App\Command;

use App\Entity\Car;
use App\Entity\Model;
use App\Entity\Stations;
use League\Csv\Reader;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Statement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvCarsImportCommand extends Command
{
    private EntityManagerInterface $ema;
    public function __construct(EntityManagerInterface $ema)
    {
        parent::__construct();

        $this->ema = $ema;
    }
    protected function configure(): void
    {
        $this
            ->setName('csv:carimport')
            ->setDescription('Import the cars_CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ios = new SymfonyStyle($input, $output);

        $ios->title('Importing the CSV feed...');

        $reader = Reader::createFromPath(__DIR__ . '/../../assets/csv/Data_voitures_electriques.csv', 'r');
        $reader->setHeaderOffset(0);

        $results = $reader->getRecords();
        $ios->progressStart(iterator_count($results));
        foreach ($results as $result) {
            //var_dump($result['geo_point_borne']);
            //die;
            $model = (new Model())
                ->setBrand($result['brand'])
                ->setModel($result['model'])
                ->setHybrid($result['is_hybrid'])
            ;

            $ios->progressAdvance(1);

            $this->ema->persist($model);
        }
        foreach ($results as $result) {
            $modelExist = $this->ema->getRepository(Model::class)
                ->findOneBy(['brand' => $result['brand'],
                    'model' => $result['model']]);
            if (!$modelExist == null) {
                $car = (new Car())
                    ->setModel($modelExist)
                    ->setColor1($result['color1'])
                    ->setColor2($result['color2'])
                ;
                $this->ema->persist($car);
                $this->ema->flush();
            }
        }

        $this->ema->flush();

        $ios->progressFinish();

        $ios->success('Everything went A-OK !');

        return Command::SUCCESS;
    }
}
