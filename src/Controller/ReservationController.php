<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{

    /**
     * @Route("/email",name="sendemail")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        $email = (new Email())
            ->from('chouchanecyrine@gmail.com')
            ->to('cyrine.chouchane@esprit.tn')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Votre Réservation')
            ->text('Votre Réservation a été prise en compte')
            ->html('<p>Votre Réservation a été prise en compte</p>');
        //->attachFromPath('/path/to/documents/pdf.pdf');
        $mailer->send($email);
        return new Response(
            'Email was sent'
        );


    }

    /**
     * @Route("/list/{id}",name="list")
     */
    public function list($id)
    {
        $reservation = $this->getDoctrine()->getRepository(Reservation::class)->findAll();
        $Events = $this->getDoctrine()->getRepository(Evenement::class)->findAll();



        return $this->render('reservation/index.html.twig',
            [
                'reservations'=>$reservation,
                'Event' => $Events,
            ]);
    }

    /**
     * @Route("/tri/date", name="tri_date")
     * @param Request $request
     * @param $reservation
     * @return Response
     */
    public function TriActionDate(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT r FROM App\Entity\Reservation r
    ORDER BY r.date ASC');



        $reservation = $query->getResult();

        return $this->render('reservation/index.html.twig', array(
            'reservations' => $reservation));

    }


    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),

        ]);
    }

    /**
     * @Route("/pdf", name="pdf", methods={"GET"})
     */
    public function pdf(ReservationRepository $reservationRepository)
    {
        $Reservations = $reservationRepository->findAll();
        // Configure Dompdf according to your need
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('Reservation/mypdf.html.twig', ['Reservations' => $Reservations]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/pdf2/{id}", name="pdf2", methods={"GET"})
     */
    public function pdf2(Reservation $reservation)
    {
        // Configure Dompdf according to your need
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('Reservation/mypdf2.html.twig', ['reservation' => $reservation]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf2.pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * @Route("/new", name="reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->sendEmail($mailer);

            return $this->redirectToRoute('reservationdone', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/yes", name="reservationdone")
     */
    public function done(): Response
    {
        return $this->render('reservation/yes.html.twig');
    }

    /**
     * @Route("/{id}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reservation_index', [], Response::HTTP_SEE_OTHER);
    }


}
