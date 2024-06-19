<?php

namespace App\Command;

use App\Entity\Stations;
use League\Csv\Reader;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Statement;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CsvImportCommand extends Command
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
            ->setName('csv:import')
            ->setDescription('Import CSV file')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $ios = new SymfonyStyle($input, $output);

        $ios->title('Importing the CSV feed...');

        $reader = Reader::createFromPath(__DIR__ . '/../../assets/csv/bornes-irve.csv', 'r');
        $reader->setDelimiter(';');
        $reader->setHeaderOffset(0);
        $stmt = Statement::create()->limit(1000)->offset(0);
        $results = $stmt->process($reader);


        //$results = $reader->getRecords();
        $ios->progressStart(iterator_count($results));
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

            $ios->progressAdvance();

            $this->ema->persist($station);
        }

        $user = (new User())
            ->setFirstname('Bob')
            ->setLastname('Daniel')
            ->setEmail('bob@bob.com')
            ->setPassword('bob')
            ->setRoles(['ROLE_USER'])
            ->setAddress('rue de l\'import')
        ;

        $this->ema->persist($user);

        $this->ema->flush();

        $ios->progressFinish();

        $ios->success('Everything went A-OK !');

        return Command::SUCCESS;
    }
}
