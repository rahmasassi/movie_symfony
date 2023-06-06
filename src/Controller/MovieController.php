<?php

namespace App\Controller;

use App\Entity\Director;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Form\RateType;
use App\Service\MovieService;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{


    /**
     * @Route("/", name="movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }



    /**
     * @Route("/rate/{id}", name="movie_rate", methods={"GET","POST"})
     */
    public function rate(Request $request,Movie $movie, $id): Response
    {
        return $this->render('movie/review.html.twig', [
            'movie' => $movie

        ]);
    }

    /**
     * @Route("/review/{id}", name="review_score", methods={"GET","POST"})
     */
    public function review(Request $request, Movie $movie, EntityManagerInterface $manager): Response
    {
            $rating = $request->request->get('stars');
            if (empty($rating))
            {
                throw new NotFoundHttpException('Expecting mandatory parameters!');
            }
            $movie->setRating($rating);
            $manager->persist($movie);
            $manager->flush();
            $this->addFlash('success','Thank You, Your movie rating has been saved');
            return $this->redirectToRoute('home');
    }

    /**
     * @Route("/new", name="movie_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $videoFile = $form['video']->getData();
            $videoFile->move(
                $this->getParameter('video_directory'),
                $videoFile->getClientOriginalName()
            );

            $movie->setVideo($videoFile->getClientOriginalName());
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'video_url' => 'video/video.mp4',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Movie $movie, EntityManagerInterface $manager): Response
    {

        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $movie = $form->getData();
            $manager->persist($movie);
            $manager->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete")
     */
    public function delete(Request $request, Movie $movie, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $manager->remove($movie);
            $manager->flush();
        }

        return $this->redirectToRoute('movie_index');
    }



}
