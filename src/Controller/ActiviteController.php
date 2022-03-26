<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Form\ActiviteType;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;

/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
{
    /**
     * @Route("/", name="activite_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }



    /**
     * @Route("/activiteback", name="indexback", methods={"GET"})
     */
    public function indexback(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/indexback.html.twig', [
            'activites' => $activiteRepository->findAll(),]);
    }

    /**
     * @Route("/new", name="activite_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);

        $form->add('statut',null, [
            'required'   => false,
            'empty_data' => 'Accepter',
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //   $file = $form->get('photo')->getData();
            //  $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            //   try {
            //      $file->move(
            //          $this->getParameter('img_directory'),
            //          $fileName
            //      );
            //    } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            //   }
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="activite_show", methods={"GET"})
     */
    public function show(Activite $activite): Response
    {
        return $this->render('activite/show.html.twig', [
            'activite' => $activite,

        ]);
    }

    /**
     * @Route("/indexback/{id}", name="activite_showback")
     */
    public function showback(Activite $activite): Response
    {
        return $this->render('activite/showback.html.twig', [
            'activite' => $activite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="activite_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="activite_delete", methods={"POST"})
     */
    public function delete(Request $request, Activite $activite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_index', [], Response::HTTP_SEE_OTHER);
    }



    /**
     * @Route("/updateStatutActivite/{id}", name="updateStatutActivite")
     */
    public function updateStatutActivite(Request $request, $id,Activite $activite)
    {
        $activite = $this->getDoctrine()->getRepository(Activite::class)->find($id);
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);
        // On récupère les données
        $donnees = $request->getContent();
        if (!$activite) {
            // On instancie un rendez-vous
            $activite = new Activite();
            // On change le code
            $code = 201;
        }
        // On hydrate l'objet avec les données
        $activite->setStatut("desactivee");

        $em = $this->getDoctrine()->getManager();
        $em->persist($activite);
        $em->flush();

        return $this->redirectToRoute('activite_index');
    }
    // }



}
