<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Repository\ProduitRepository;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Lcobucci\JWT\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\User ;
use App\Entity\Produit ;
use App\Entity\Commande ;
use App\Entity\Panier ;
use App\Entity\Adresse ;
use App\Entity\Livreur ;

use App\Services\QrcodeService;



class MobileController extends AbstractController
{
    /**
     * @Route("/displayProduitMobile", name="displayProduitMobile")
     */
    public function displayProduitMobile(Request $request, SerializerInterface $serializer): Response
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findAll();
        $formatted = $serializer->normalize($produits,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addProduitMobile", name="addProduitMobile")
     */
    public function addProduitMobile(Request $request, SerializerInterface $serializer): Response
    {

        $produit = new Produit();
        $nom=$request->query->get("nom") ;
        $prix=$request->query->get("prix") ;
        $description=$request->query->get("description") ;
        $disponibilite=$request->query->get("disponibilite") ;
        $photo=$request->query->get("photo") ;

        $produit->setNom($nom) ;
        $produit->setPrix($prix) ;
        $produit->setDescription($description) ;
        $produit->setDisponibilte($disponibilite) ;
        $produit->setPhoto($photo) ;


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        $formatted = $serializer->normalize($produit,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteProduitMobile", name="deleteProduitMobile")
     */
    public function deleteProduitMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $produit = $entityManager->getRepository(Produit::class)->find($id);
        if($produit!=null){
            $entityManager->remove($produit);
            $entityManager->flush();
           // $formatted = $serializer->normalize($produit,'json',['groups' => 'post:read']);
            return new Response("del") ;

        }


        return new Response(" produit invalide") ;
    }
    /**
     * @Route("/updateProduitMobile", name="updateProduitMobile")
     */
    public function updateProduitMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $produit = $entityManager->getRepository(Produit::class)->find($request->get("id"));
        $nom=$request->query->get("nom") ;
        $prix=$request->query->get("prix") ;
        $description=$request->query->get("description") ;
        $disponibilite=$request->query->get("disponibilite") ;
        $photo=$request->query->get("photo") ;
        $produit->setNom($nom) ;
        $produit->setPrix($prix) ;
        $produit->setDescription($description) ;
        $produit->setDisponibilte($disponibilite) ;
        $produit->setPhoto($photo) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($produit);
        $entityManager->flush();

        $formatted = $serializer->normalize($produit,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }

    //////commandes
    /**
     * @Route("/displayCommandesMobile", name="displayCommandesMobile")
     */
    public function displayCommandesMobile(Request $request, SerializerInterface $serializer): Response
    {
        $cmd = $this->getDoctrine()->getRepository(Commande::class)->findAll();
        $formatted = $serializer->normalize($cmd,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/displayPanierMobile", name="displayPanierMobile")
     */
    public function displayPanierMobile(Request $request, SerializerInterface $serializer): Response
    {
        $cmd = $this->getDoctrine()->getRepository(Panier::class)->findAll();
        $formatted = $serializer->normalize($cmd,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addAdresseMobile", name="addAdresseMobile")
     */
    public function addAdresseMobile(Request $request, SerializerInterface $serializer,SessionInterface $session,ProduitRepository $produitRepository): Response
    {
        $panier = $session->get("panier", []);
        $dataPanier = [];
        $total = 0;
        $produits =[];
        $em = $this->getDoctrine()->getManager();
        $adresse = new Adresse();

        $nom=$request->query->get("nom") ;
        $prenom=$request->query->get("prenom") ;
        $adress=$request->query->get("adress") ;
        $city=$request->query->get("city") ;
        $mail=$request->query->get("email") ;
        $tel=$request->query->get("tel") ;

        $adresse->setNom($nom) ;
        $adresse->setPrenom($prenom) ;
        $adresse->setAdress($adress) ;
        $adresse->setCity($city) ;
        $adresse->setEmail($mail) ;
        $adresse->setTel($tel) ;

        foreach ($panier as $id => $quantite) {
            $product = $produitRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];
            $total += $product->getPrix() * $quantite;

            $commande = new Commande();
            $commande->setTotal($product->getPrix() * $quantite);
            $commande->setProduit($product);
            $commande->setIdLivreur(null);
            $commande->setQuantite($quantite);


        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($adresse);
        $entityManager->flush();

        $formatted = $serializer->normalize($adresse,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/displayLivreurMobile", name="displayLivreurMobile")
     */
    public function displayLivreurMobile(Request $request, SerializerInterface $serializer): Response
    {
        $l = $this->getDoctrine()->getRepository(Livreur::class)->findAll();
        $formatted = $serializer->normalize($l,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addLivreurMobile", name="addLivreurMobile")
     */
    public function addLivreurMobile(Request $request, SerializerInterface $serializer): Response
    {

        $liv = new Livreur();
        $nom=$request->query->get("nom") ;
        $prenom=$request->query->get("prenom") ;
        $tel=$request->query->get("tel") ;
        $email=$request->query->get("email") ;

        $liv->setNom($nom) ;
        $liv->setPrenom($prenom) ;
        $liv->setTel($tel) ;
        $liv->setEmail($email) ;


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($liv);
        $entityManager->flush();

        $formatted = $serializer->normalize($liv,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/updateLivreurMobile", name="updateLivreurMobile")
     */
    public function updateLivreurMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $liv = $entityManager->getRepository(Livreur::class)->find($request->get("id"));
        $nom=$request->query->get("nom") ;
        $prenom=$request->query->get("prenom") ;
        $tel=$request->query->get("tel") ;
        $email=$request->query->get("email") ;

        $liv->setNom($nom) ;
        $liv->setPrenom($prenom) ;
        $liv->setTel($tel) ;
        $liv->setEmail($email) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($liv);
        $entityManager->flush();

        $formatted = $serializer->normalize($liv,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/deleteLivreurMobile", name="deleteLivreurMobile")
     */
    public function deleteLivreurMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $liv = $entityManager->getRepository(Livreur::class)->find($id);
        if($liv!=null){
            $entityManager->remove($liv);
            $entityManager->flush();
            $formatted = $serializer->normalize($liv,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" livreur invalide") ;
    }
    /**
     * @Route("/signupMobile", name="signupMobile")
     */
    public function signup(Request $request, UserPasswordEncoderInterface $encoder, SerializerInterface $serializer): Response
    {

        $email = $request->query->get("email");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $password = $request->query->get("password");
        $phone = $request->query->get("GSM");
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("email invalid");
        }
        $user = new User();
        $user->setEmail($email);
        $user->setGSM($phone);
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setRoles(['ROLE_USER']);

        $user->setDateNaissance(new \DateTime());
        $user->setPassword($password);
        try {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse("Account is created", 200);
        } catch (\Exception$ex) {
            return new Response("exception" . $ex->getMessage());
        }


    }
    /**
     * @Route("/signinMobile", name="signinMobile")
     */
    public function signinAction(Request $request,SerializerInterface $serializer)
    {
        $email = $request->query->get("email");
        $password = $request->query->get("password");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            if ($password==$user->getPassword()) {
                $formatted = $serializer->normalize($user, 'json', ['groups' => 'post:read']);
                return new Response(json_encode($formatted));

            }
            else {

                return new Response("passowrd not found");
            }
        }
        else {
            return new Response("user not found");
        }

    }
    //partie user
    /**
     * @Route("/editUserMobile", name="editUserMobile")
     */
    public function editUserMobile(Request $request,SerializerInterface $serializer, UserPasswordEncoderInterface $encoder)
    {
        $id=$request->query->get("id");
        $email = $request->query->get("email");
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $password = $request->query->get("password");
        $phone = $request->query->get("GSM");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($request->files->get("imageFile")==null) {
            $file = $request->files->get("imageFile");
            //   $filename = $file->getClientOriginalName();
            //   $file->move($filename);
            //  $user->setImageFile($filename);
            $user->setEmail($email);
            $user->setGSM($phone);
            $user->setNom($nom);
            $user->setPrenom($prenom);
            $user->setPassword($password);
        }
        try{
            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return new JsonResponse("success",200);
        }catch(\Exception $ex){
            return new Response("fail".$ex->getMessage());
        }



    }
    /**
     * @Route("/passwordMobile", name="passwordMobile")
     */
    public function getPasswordbyPhone(Request $request,SerializerInterface $serializer)
    {
        $phoneNumber = $request->query->get("GSM");
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->findOneBy(['GSM' => $phoneNumber]);
        if ($user) {
            $password=$user->getPassword();
            $formatted = $serializer->normalize($password, 'json', ['groups' => 'post:read']);
            return new Response(json_encode($formatted));

        }
        else {
            return new Response("user not found");
        }

    }
    /**
     *@Route ("/displayEvenements", name="display_evenement")
     */
    public function allEvenementAction()
    {
        $event = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->findAll();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);

    }


    /**
     *@Route ("/addEvenement", name="add_evenement")
     *@Method("POST")
     */
    public function ajouterEvenementAction(Request $request)
    {
        $evenement= new Evenement();
        $nom=$request->query->get("nom");
        $photo=$request->query->get("photo");
        $description=$request->query->get("description");
        $lieu=$request->query->get("lieu");
        $prix=$request->query->get("prix");
        $em=$this->getDoctrine()->getManager();

        $evenement->setNom( $nom);
        $evenement->setPhoto($photo);
        $evenement->setDescription($description);
        $evenement->setLieu($lieu);
        $evenement->setPrix($prix);

        $em->persist($evenement);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse($formatted);


    }
    /**
     *@Route ("/deleteEvenement", name="delete_evenement")
     *@Method("DELETE")
     */
    public function deleteEvenementAction(Request $request){
        $id=$request->get("id");

        $em=$this->getDoctrine()->getManager();
        $evenement=$em->getRepository(Evenement::class)->find($id);
        if($evenement!=null) {
            $em->remove($evenement);
            $em->flush();

            $serialize = new Serializer ([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Evenement a ete supprime avec succes");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id evenement invalide");
    }

    /**
     *@Route ("/updateEvenement", name="update_evenement")
     *@Method("PUT")
     */
    public function modifierEvenementAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $evenement=$this->getDoctrine()->getManager()
            ->getRepository(Evenement::class)
            ->find($request->get("id"));

        $evenement->setNom($request->get("nom"));
        $evenement->setPhoto($request->get("photo"));
        $evenement->setDescription($request->get("description"));
        $evenement->setLieu($request->get("lieu"));
        $evenement->setPrix($request->get("prix"));

        $em->persist($evenement);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse("Evenement a ete modifie avec succes");


    }

    /**
     * @Route("/detailEvenement", name="detail_evenement")
     * @Method ("GET")
     */

    public function detailEvenementAction(Request $request)
    {
        $id = $request->get("id");


        $em = $this->getDoctrine()->getManager();
        $evenement = $this->getDoctrine()->getManager()->getRepository(Evenement::class)->find($id);
        $encoder = new JsonEncoder();

        $jsonContent =   $normalizer = new ObjectNormalizer();

        $jsonContent = $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getDescription(); });
        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($evenement);
        return new JsonResponse($formatted);    }
    /**
     *@Route ("/displayReservations", name="display_reservation")
     */
    public function allReservationAction()
    {
        $reserv = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->findAll();
        $encoder = new JsonEncoder();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $serializer = new Serializer([$normalizer],[$encoder]);
        $formatted = $serializer->normalize($reserv);
        return new JsonResponse($formatted);

    }


    /**
     *@Route ("/addReservation", name="add_Reservation")
     *@Method("POST")
     */
    public function ajouterReservationAction(Request $request)
    {
        $Reservation= new Reservation();
        $nbr_pers=$request->query->get("nbr_pers");
        $date=$request->query->get("date");
        $date_r=$request->query->get("date_r");
        $Evenement=$request->query->get("Evenement");
        $em=$this->getDoctrine()->getManager();

        $Reservation->setNbrPers( $nbr_pers);
        $Reservation->setDate($date);
        $Reservation->setDateR($date_r);
        $Reservation->setEvenement($Evenement);

        $em->persist($Reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Reservation);
        return new JsonResponse($formatted);


    }
    /**
     *@Route ("/deleteReservation", name="delete_Reservation")
     *@Method("DELETE")
     */
    public function deleteReservationAction(Request $request){
        $id=$request->get("id");

        $em=$this->getDoctrine()->getManager();
        $Reservation=$em->getRepository(Reservation::class)->find($id);
        if($Reservation!=null) {
            $em->remove($Reservation);
            $em->flush();

            $serialize = new Serializer ([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Reservation a été supprimé avec succés");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id Reservation invalide");
    }

    /**
     *@Route ("/updateReservation", name="update_Reservation")
     *@Method("PUT")
     */
    public function modifierReservationAction(Request $request){
        $em=$this->getDoctrine()->getManager();
        $Reservation=$this->getDoctrine()->getManager()
            ->getRepository(Reservation::class)
            ->find($request->get("id"));


        $Reservation->setNbr_pers($request->get("nbr_pers"));
        $Reservation->setDate($request->get("date"));
        $Reservation->setDateR($request->get("date_r"));
        $Reservation->setEvenement($request->get("evenement"));

        $em->persist($Reservation);
        $em->flush();
        $serializer=new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Reservation);
        return new JsonResponse("Reservation a été modifié avec succés");


    }

    /**
     * @Route("/detailReservation", name="detail_Reservation")
     * @Method ("GET")
     */

    public function detailReservationAction(Request $request)
    {
        $id=$request->get("id");

        $em=$this->getDoctrine()->getManager();
        $Reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($id);
        $encoder=new JsonEncoder();
        $normalizer=new ObjectNormalizer();
        $normalizer->setCircularReferenceHandler(function ($object){
            return $object->getNbrPers();
        });
    }
}