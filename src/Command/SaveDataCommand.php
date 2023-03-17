<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Fruit;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:save-data')]
class SaveDataCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
        parent::__construct();
    }
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $client = HttpClient::create();
        $response = $client->request('GET', 'https://fruityvice.com/api/fruit/all');

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $fruits = $response->getContent();
        $fruits = $response->toArray();

        foreach ($fruits as $content) {
            $fruit = new Fruit();

            foreach ($content as $key => $value) {
                if ($key == 'nutritions') {
                    foreach ($value as $nut_key => $nut_value) {
                        $col_name = $nut_key;
                        $col_value = $nut_value;
                        switch ($col_name) {
                            case 'carbohydrates':
                                $fruit->setCarbohydrates($col_value);
                                break;
                            case 'protein':
                                $fruit->setProtein($col_value);
                                break;
                            case 'fat':
                                $fruit->setFat($col_value);
                                break;
                            case 'calories':
                                $fruit->setCalories($col_value);
                                break;
                            case 'sugar':
                                $fruit->setSugar($col_value);
                                break;
                            default:
                                break;
                        }
                    }
                } else {
                    $col_name = $key;
                    $col_value = $value;
                    switch ($col_name) {
                        case 'genus':
                            $fruit->setGenus($col_value);
                            break;
                        case 'name':
                            $fruit->setName($col_value);
                            break;
                        case 'id':
                            $fruit->setFruitId($col_value);
                            break;
                        case 'family':
                            $fruit->setFamily($col_value);
                            break;
                        case 'order':
                            $fruit->setFruitOrder($col_value);
                            break;
                        default:
                            break;
                    }
                }
                $this->entityManager->persist($fruit);
                $this->entityManager->flush();

                $output->writeln($col_name . " " . $col_value);
            }
            $output->write('Data saved successfully!');
        }


        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }

    protected function configure(): void
    {
        $this
            // the command help shown when running the command with the "--help" option
            ->setHelp('This command allows you to save all fruits data to the database.');
    }
}
