<?php

namespace App\Command;

use App\Omdb\OmdbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MovieSeachCommand extends Command
{
    protected static $defaultName = 'app:movie:search';
    protected static $defaultDescription = 'Find a movie by its name';
    private $omdbClient;

    public function __construct(OmdbClient $omdbClient, string $name = null)
    {
        $this->omdbClient = $omdbClient;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('title', InputArgument::OPTIONAL, 'Seach all movies matching movie_name')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL,'Type de média à afficher')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if(!$title = $input->getArgument('title')){
           $title = $io->ask('Quel film souhaitez-vous chercher?', 'Matrix', function($answer){
               if(strlen($answer) < 3){
                   throw new \InvalidArgumentException('La recherche est trop courte');
               }
           });
        }

        if(!$type = $input->getOption('type')){
            $type = $io->choice('Quel type de média voulez-vous afficher?', ['movie', 'series', 'episode', 'game', 'tous']);
        }

        $io->title('Film recherché: '. $title);

        $searchOptions = ('tous' === $type) ? [] : ['type' => $type];

        $movies = $this->omdbClient->requestBySearch($title, $searchOptions)['Search'];

        $io->progressStart(count($movies));

        foreach($movies as $movie){
            $rows[] = [$movie['Title'], $movie['Year'], $movie['Type'], 'https://www.imdb.com/title/'.$movie['imdbID']];
            usleep(100000); // pour ralentir la recherche pour la barre de progression
            $io->progressAdvance(1);
        }

        $io->write("\r"); // Pour effacer la barre de progression

        $io->table(['Titre', 'Sorti en', 'Type', 'Fiche film'], $rows);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
