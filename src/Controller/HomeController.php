<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MovieRepository;



class HomeController extends AbstractController
{
    /**
     * @Route("/",name="app_homepage")
     */
    public function homepage(MovieRepository $movieRepository){

       return $this -> render('home/homepage.html.twig',[
           'movies' => $movieRepository->findAll(),
       ]);

    }

    /**
     * @Route("/homeadmin",name="home_admin")
     */
    public function homeAdmin(){

        return $this -> render('home/homeadmin.html.twig');

    }


    /**
     * @Route("/homeclient",name="home")
     */
    public function homeClient(MovieRepository $movieRepository){

        return $this -> render('home/homeclient.html.twig',[
            'movies' => $movieRepository->findAll(),
        ]);

    }

}