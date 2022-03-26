<?php

namespace App\Controller;

use App\Entity\CentreCamp;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\CentreCampType;
use App\Repository\CentreCampRepository;
use PHPUnit\TextUI\XmlConfiguration\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



/**
 * @Route("/centre/camp")
 *
 */
class CentreCampController extends AbstractController
{
    /**
     * @Route("/", name="app_centre_camp_index",  methods={"GET"})
     */
    public function index(CentreCampRepository $centreCampRepository): Response
    {
        return $this->render('centre_camp/index.html.twig', [
            'centre_camps' => $centreCampRepository->findAll(),
        ]);
    }
    /**
     * @Route("/b", name="app",  methods={"GET"})
     */
    public function indexfronttt(CentreCampRepository $centreCampRepository): Response
    {
        return $this->render('base-front.html.twig', [
            'centre_camps' => $centreCampRepository->findAll(),
        ]);
    }

    public function indexfrontcentre(CentreCampRepository $centreCampRepository): Response
    {
        return $this->render('centre_camp/showfront.html.twig', [
            'centre_camps' => $centreCampRepository->findAll(),
        ]);
    }


    /**
     * @Route("/indexback", name="app_centre_camp_indexback",  methods={"GET"})
     */
    public function indexback(CentreCampRepository $centreCampRepository): Response
    {
        return $this->render('centre_camp/indexback.html.twig', [
            'centre_camps' => $centreCampRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_centre_camp_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CentreCampRepository $centreCampRepository): Response
    {
        $centreCamp = new CentreCamp();
        $form = $this->createForm(CentreCampType::class, $centreCamp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $centreCamp->getImgCentre();

            $filename= md5(uniqid()).'.'.$file-> guessExtension();
            $entityManager = $this->getDoctrine()->getManager();
            $centreCamp->setImgCentre("ee");
            $entityManager->persist($centreCamp);
            $entityManager->flush();
            try {
              
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $centreCampRepository->add($centreCamp);
            return $this->redirectToRoute('app_centre_camp_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('centre_camp/new.html.twig', [
            'centre_camp' => $centreCamp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_centre_camp_show",  methods={"GET"})
     */
    public function show(CentreCamp $centreCamp): Response
    {
        return $this->render('centre_camp/show.html.twig', [
            'centre_camp' => $centreCamp,
        ]);
    }
    /**
     * @Route("/showfront/{id}", name="app_centre_camp_showfront" )
     */
    public function showfront(CentreCamp $centreCamp): Response
    {
        return $this->render('centre_camp/showfront.html.twig', [
            'centre_camp' => $centreCamp,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_centre_camp_edit",  methods={"GET","POST"})
     */
    public function edit(Request $request, CentreCamp $centreCamp, CentreCampRepository $centreCampRepository): Response
    {
        $form = $this->createForm(CentreCampType::class, $centreCamp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $centreCampRepository->add($centreCamp);
            return $this->redirectToRoute('app_centre_camp_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('centre_camp/edit.html.twig', [
            'centre_camp' => $centreCamp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_centre_camp_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, CentreCamp $centreCamp, CentreCampRepository $centreCampRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$centreCamp->getId(), $request->request->get('_token'))) {
            $centreCampRepository->remove($centreCamp);
        }

        return $this->redirectToRoute('app_centre_camp_indexback', [], Response::HTTP_SEE_OTHER);
    }


    /*
      public function recherchebylieux(Request $request)
      {

        $em=$this->getDoctrine()->getManager();
        $centrecamp = $em->getRepository(CentreCamp::class)->findAll();
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
       //initialement le tableau des articles est vide,
       //c.a.d on affiche les articles que lorsque l'utilisateur clique sur le bouton rechercher
        $Places= [];

       if($form->isSubmitted() && $form->isValid()) {
       //on récupère le nom d'article tapé dans le formulaire
        $place = $propertySearch->getlieu();
        if ($place!="")
          //si on a fourni un lieu on affiche tous les articles ayant ce nom
          $Places= $this->getDoctrine()->getRepository(CentreCamp::class)->findBy(['lieux' => $place] );
        else
          //si si aucun nom n'est fourni on affiche tous les articles
          $Places= $this->getDoctrine()->getRepository(CentreCamp::class)->findAll();

        return  $this->render('centre_camp/recherche.html.twig', array('centrecamp'=>$centrecamp));
      }  */
}


