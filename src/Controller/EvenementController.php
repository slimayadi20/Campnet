<?php

namespace App\Controller;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Annotation\Groups;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use symfony\Component\Validator\Constraints\Json;




/**
 * @Route("/evenement")
 */

class EvenementController extends AbstractController
{






//


//
    //favoris
    /*/**ment/evenment/add
     * @Route("/favoris/ajout/{id}", name="ajout_favoris")
     */
    /*  public function ajoutFavoris(Annonces $evenement)
      {
          if(!$evenement){
              throw new NotFoundHttpException('Pas d\'evenements trouvée');
          }
          $evenement->addFavori($this->getUser());

          $em = $this->getDoctrine()->getManager();
          $em->persist($evenement);
          $em->flush();
          return $this->redirectToRoute('evenement_index');
      }

   /*   /**
       * @Route("/favoris/retrait/{id}", name="retrait_favoris")
       */
    /*public function retraitFavoris(Annonces $evenement)
    {
        if(!$evenement){
            throw new NotFoundHttpException('Pas d\'evenement trouvée');
        }
        $evenement->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();
        return $this->redirectToRoute('evenement_index');
    }

    private function fonctionLongue(){
        sleep(3);
        return "Brouette";
    }
}
    */

    //




    //
    /*  /**
       * Search action.
       * @Route("/search/{search}", name="search")
       * @param  Request               $request Request instance
       * @param  string                $search  Search term
       * @return Response|JsonResponse          Response instance
       */
    /*public function searchhAction(Request $request, string $search)
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->render("search.html.twig");
        }

        if (!$searchTerm = trim($request->query->get("search", $search))) {
            return new JsonResponse(["error" => "Search term not specified."], Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        if (!($results = $em->getRepository(Evenement::class)->findOneBynom($searchTerm))) {
            return new JsonResponse(["error" => "No results found."], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse([
            "html" => $this->renderView("search.html.twig", ["results" => $results]),
        ]);
    }
*/

    /**
     * @Route("/recherche", name="recherche_evenement")
     * @param Request $request
     * @param $evenement
     * @return Response
     */
    public function searchAction(Request $request)
    {

        $data = $request->request->get('search');


        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('SELECT e FROM App\Entity\Evenement e WHERE e.nom LIKE :data')
            ->setParameter('data', '%'.$data.'%');

        $evenement = $query->getResult();

        return $this->render('evenement/index.html.twig', array(
            'evenements' => $evenement));

    }


    /**
     * @Route("/", name="evenement_index", methods={"GET","POST"})
     */
    public function index(EvenementRepository $evenementRepository): Response
    {
        return $this->render('evenement/index.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'val' => '',
            'res' => []
        ]);
    }


    //
    /**
     * @Route("/list", name="evenement_list", methods={"GET","POST"})
     */
    public function list (EvenementRepository $EvenementRepository): Response
    {
        $evenements = $this->getDoctrine()->getRepository(Evenement::class)->findAll();


        return $this->render('evenement/list.html.twig',
            [
                'evenements' => $evenements,
            ]);
    }





    //
    /**
     * @Route("/new", name="evenement_new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {

                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $evenement->setPhoto("ee");
            }

            $entityManager->persist($evenement);
            $entityManager->flush();




            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_show", methods={"GET"})
     */
    public function show(Evenement $evenement): Response
    {
        return $this->render('evenement/show.html.twig', [
            'evenement' => $evenement,
        ]);
    }

    /**
     * @Route("/map/{id}", name="event_show_user", methods={"POST","GET"})
     */
    public function showmap(Evenement $event): Response
    {

        if (isset($_POST["submit_address"]))
        {
            $address = $_POST["address"];
            $address = str_replace(" ", "+", $address);
            ?>

            <iframe width="100%" height="500" src="https://maps.google.com/maps?q=<?php echo $address; ?>&output=embed"></iframe>

            <?php
        }
        //return $this->render('event/map.html.twig');

        return $this->render('evenement/show.html.twig', [
            'evenement' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $evenement->setPhoto($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenement_index', [], Response::HTTP_SEE_OTHER);
    }



}