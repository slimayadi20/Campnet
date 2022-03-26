<?php

namespace App\Controller;

use App\Entity\Promo;
use App\Form\PromoType;
use App\Repository\PromoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/promo")
 */
class PromoController extends AbstractController
{
    /**
     * @Route("/indexfront", name="app_promo_indexfront", methods={"GET"})
     */
    public function index(PromoRepository $promoRepository): Response
    {
        return $this->render('promo/indexfront.html.twig', [
            'promos' => $promoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/indexback", name="app_promo_indexback", methods={"GET"})
     */
    public function indexback(PromoRepository $promoRepository): Response
    {
        return $this->render('promo/indexback.html.twig', [
            'promos' => $promoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_promo_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PromoRepository $promoRepository, \Swift_Mailer $Mailer): Response
    {
        $promo = new Promo();
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promoRepository->add($promo);
            $message= (new Swift_Message('nouveau Promo'))
                // on attribut l'epéditeur
                ->setFrom('ihebbt@gmail.com')
                //on attribut le destinataire
                ->setTo('ihebbt@gmail.com')
                // on crée le message la vue Twig
                ->setBody($this->renderView(
                    'emails/Promo.html.twig'
                ), 'text/html'


                );

            ; //on envoie le message
            $Mailer->send($message);
            return $this->redirectToRoute('app_promo_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promo/new.html.twig', [
            'promo' => $promo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_promo_show", methods={"GET"})
     */
    public function show(Promo $promo): Response
    {
        return $this->render('promo/show.html.twig', [
            'promo' => $promo,
        ]);
    }

    /**
     * @Route("/showfront/{id}", name="app_promo_showfront", methods={"GET"})
     */
    public function showfront(Promo $promo): Response
    {
        return $this->render('promo/showfront.html.twig', [
            'promo' => $promo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_promo_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Promo $promo, PromoRepository $promoRepository): Response
    {
        $form = $this->createForm(PromoType::class, $promo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $promoRepository->add($promo);
            return $this->redirectToRoute('app_promo_indexback', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('promo/edit.html.twig', [
            'promo' => $promo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_promo_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Promo $promo, PromoRepository $promoRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promo->getId(), $request->request->get('_token'))) {
            $promoRepository->remove($promo);
        }

        return $this->redirectToRoute('app_promo_indexback', [], Response::HTTP_SEE_OTHER);
    }
}
