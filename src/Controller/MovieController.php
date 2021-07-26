<?php

namespace App\Controller;

use App\Omdb\OmdbClient;
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
    public $omdbClient;

    public function __construct(HttpClientInterface $httpClient){
      $apiKey = '28c5b7b1';
      $omdbHost = 'https://www.omdbapi.com';
      $this->omdbClient = new OmdbClient($httpClient, $apiKey, $omdbHost);
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
    public function movieDetails($imdbID): Response
    {
      $movie = $this->omdbClient->requestById($imdbID);
      dump($movie);

      return $this->render('movie/details.html.twig', [
        'imdbID' => $imdbID,
        'movie' => $movie,
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
