<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Class MovieController
 *
 * @Route("/movie", name="movie")
 */
class MovieController extends AbstractController
{
    private $omdbClient;

    public function __construct(OmdbClient $omdbClient)
    {
      $this->omdbClient = $omdbClient;
    }

    /**
     * @Route("/", name="movie")
     */
    public function index(): Response
    {
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieController',
        ]);
    }

    /**
     * Movie details
     * @Route("/{imdbID}", name="details", requirements={"imdbID"="tt\d+"}, methods={"GET"})
     */
    public function movieDetails($imdbID, MovieRepository $movieRepository): Response
    {
      $movie = $this->omdbClient->requestById($imdbID);
      $movieFromDb = $movieRepository->findOneBy(['title' => 'Memento']);
      dump($movie, $movieFromDb);

      return $this->render('movie/details.html.twig', [
          'imdbID' => $imdbID,
          'movie' => $movie,
          'movieFromDb' => $movieFromDb
      ]);
    }

    /**
     * Top rated
     * @Route("/top-rated", name="top_rated")
     */
    public function movieTopRated(): Response
    {
      $movies = $this->omdbClient->requestBySearch('matrix');
      dump($movies);
      return $this->render('movie/top-rated.html.twig', ['movies' => $movies['Search']]);
    }

    /**
     * Genres
     * @Route("/genres", name="genres")
     */
    public function movieGenres(): Response
    {
      return $this->render('movie/genres.html.twig');
    }

    /**
     * Top rated
     * @Route("/latest", name="latest")
     */
    public function movieLatest(): Response
    {
      return $this->render('movie/latest.html.twig');
    }
}
