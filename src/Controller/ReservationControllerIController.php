<?php

namespace App\Controller;

use App\Entity\ReservationI;
use App\Form\ReservationIType;
use App\Repository\ReservationIRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/reservationI")
 */
class ReservationControllerIController extends AbstractController
{
    /**
     * @Route("/front", name="app_reservation_index", methods={"GET"})
     */
    public function index(ReservationIRepository $reservationRepository): Response
    {
        return $this->render('reservation_controller_i/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/back", name="app_reservation_indexback", methods={"GET"})
     */
    public function indexback(ReservationIRepository $reservationRepository): Response
    {
        return $this->render('reservation_controller_i/indexback.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReservationIRepository $reservationRepository): Response
    {
        $reservation = new ReservationI();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_controller_i/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show", name="app_reservation_show", methods={"GET"})
     */
    public function show(ReservationI $reservation): Response
    {
        return $this->render('reservation_controller_i/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/showfront", name="app_reservation_showfront", methods={"GET"})
     */
    public function showfront(ReservationI $reservation): Response
    {
        return $this->render('reservation_controller_i/showfront.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReservationI $reservation, ReservationIRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationTypeI::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->add($reservation);
            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation_controller_i/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, ReservationI $reservation, ReservationIRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
