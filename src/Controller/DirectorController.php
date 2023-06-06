<?php

namespace App\Controller;

use App\Entity\Director;
use App\Form\DirectorType;
use App\Repository\DirectorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/director")
 */
class DirectorController extends AbstractController
{
    /**
     * @Route("/", name="director_index", methods={"GET"})
     */
    public function index(DirectorRepository $directorRepository): Response
    {
        return $this->render('director/index.html.twig', [
            'directors' => $directorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="director_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $director = new Director();
        $form = $this->createForm(DirectorType::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $director = $form->getData();
            $manager->persist($director);
            $manager->flush();

            return $this->redirectToRoute('director_index');
        }

        return $this->render('director/new.html.twig', [
            'director' => $director,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="director_show", methods={"GET"})
     */
    public function show(Director $director): Response
    {
        return $this->render('director/show.html.twig', [
            'director' => $director,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="director_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Director $director,EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(DirectorType::class, $director);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $director = $form->getData();
            $manager->persist($director);
            $manager->flush();
            return $this->redirectToRoute('director_index');
        }

        return $this->render('director/edit.html.twig', [
            'director' => $director,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="director_delete")
     */
    public function delete(Request $request, Director $director,EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$director->getId(), $request->request->get('_token'))) {
            $manager->remove($director);
            $manager->flush();
        }

        return $this->redirectToRoute('director_index');
    }

}
