<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Repository\EvenementRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalendrierController extends AbstractController
{

    /**
     * @Route("/calendrier", name="calendrier")
     */
    public function index(ReservationRepository $list_reservation): Response
    {

        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        $rdvs = [];


        foreach ($reservation as $reservation) {

            $rdvs [] = [
                'id' => $reservation->getId(),
                'date' => $reservation->getDate()->format('Y-m-d'),
                'dateR' => $reservation->getDateR()->format('Y-m-d'),

            ];
        }




        $data = json_encode($rdvs);
        return $this->render('calendrier/index.html.twig', compact('data'));
    }
}

