<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Categorie;
use App\Entity\CentreCamp;
use App\Entity\Commentaire;
use App\Entity\Promo;
use App\Entity\User ;
use App\Entity\Produit ;
use App\Entity\Commande ;
use App\Entity\Panier ;
use App\Entity\Adresse ;
use App\Entity\Livreur ;
use App\Entity\Evenement;
use App\Entity\Reservation;
use App\Entity\Reclamation;
use App\Form\ResetPassType;
use App\Repository\EvenementRepository;
use App\Repository\UserRepository;
use App\Services\QrcodeService;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use App\Repository\ProduitRepository;
use Snipe\BanBuilder\CensorWords;
use Swift_Message;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class MobileController extends AbstractController
{
    // cyrine sarra oussema
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
    public function passwordMobile( UserRepository $users,SerializerInterface $serializer,Request $request, \Swift_Mailer $mailer)
    {

        $email = $request->query->get("email");

        // On cherche un utilisateur ayant cet e-mail
            $user = $users->findOneByEmail($email);

        if ($user) {
            try {
                $password = $user->getPassword();
                $message = (new \Swift_Message('Mot de passe oublié'))
                    ->setFrom('votre@adresse.fr')
                    ->setTo($user->getEmail())
                    ->setBody(
                        "Bonjour,<br><br>Votre mdp est le suivant : " . $password,
                        'text/html'
                    );

                // On envoie l'e-mail
                $mailer->send($message);

                $formatted = $serializer->normalize($password, 'json', ['groups' => 'post:read']);
                return new Response(json_encode($formatted));
            } catch (\Exception $e) {
                return new Response("error");
            }
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

    public function QR( $id,EvenementRepository $repository){



        $result = Builder::create()
            ->writer(new PngWriter())
            ->writerOptions([])
            ->data("new participant with name = cyrine")
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(200)
            ->margin(10)
            ->build();
        header('Content-Type: '.$result->getMimeType());
        $result->saveToFile('QRcode/'.'cyrine'.'.png');
    }
    public function email( \Swift_Mailer $mailer)
    {

        $message = (new \Swift_Message('confirmation de reservation pour evenement '))
            ->setFrom('slim.ayadi@esprit.tn')
            ->setTo('slim.ayadi@esprit.tn');
        $img = $message->embed(\Swift_Image::fromPath('QRcode/clientCYRINE'.'.png'));

        $message
            ->setBody("new event");



        $mailer->send($message);

    }
    /**
     *@Route ("/addEvenement", name="add_evenement")
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
     */
    public function ajouterReservationAction( QrcodeService $qrcodeService,Request $request,\Swift_Mailer $mailer)
    {
        $Reservation= new Reservation();
        $nbr_pers=$request->query->get("nbr_pers");
        $date=$request->query->get("date");
        $date_r=$request->query->get("date_r");
        $entityManager = $this->getDoctrine()->getManager();
        $qrCode = $qrcodeService->qrcode();
        $this->email($mailer);
      //  $Evenement = $entityManager->getRepository(Reservation::class)->find($request->get("Evenement"));
        $em=$this->getDoctrine()->getManager();

        $Reservation->setNbrPers( $nbr_pers);
        $Reservation->setDate(new \DateTime($date));
        $Reservation->setDateR(new \DateTime($date_r));
        $Reservation->setEvenement($request->get("Evenement"));

        $em->persist($Reservation);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($Reservation);
        return new JsonResponse($formatted);


    }
    /**
     *@Route ("/deleteReservation", name="delete_Reservation")
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
   // olfa nermine iheb
    /**
     * @Route("/displayCentreCampMobile", name="displayCentreCampMobile")
     */
    public function displayCentreCampMobile(Request $request, SerializerInterface $serializer): Response
    {
        $CentreCamp = $this->getDoctrine()->getRepository(CentreCamp::class)->findAll();
        $formatted = $serializer->normalize($CentreCamp,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addCentreCampMobile", name="addCentreCampMobile")
     */
    public function addCentreCampMobile(Request $request, SerializerInterface $serializer): Response
    {

        $CentreCamp = new CentreCamp();
        $nom_centre=$request->query->get("nom_centre") ;
        $Description_centre=$request->query->get("Description_centre") ;
        $img_centre=$request->query->get("img_centre") ;
        $lieux=$request->query->get("lieux") ;
        $tlf_centre=$request->query->get("tlf_centre") ;
        $mail_centre=$request->query->get("mail_centre") ;
        $mdps_centre=$request->query->get("mdps_centre") ;
        $CentreCamp->setNomCentre($nom_centre) ;
        $CentreCamp->setDescriptionCentre($Description_centre) ;
        $CentreCamp->setImgCentre($img_centre) ;
        $CentreCamp->setLieux($lieux) ;
        $CentreCamp->setTlfCentre($tlf_centre) ;
        $CentreCamp->setMailCentre($mail_centre) ;
        $CentreCamp->setMdpsCentre($mdps_centre) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($CentreCamp);
        $entityManager->flush();

        $formatted = $serializer->normalize($CentreCamp,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteCentreCampMobile", name="deleteCentreCampMobile")
     */
    public function deleteCentreCampMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $CentreCamp = $entityManager->getRepository(CentreCamp::class)->find($id);
        if($CentreCamp!=null){
            $entityManager->remove($CentreCamp);
            $entityManager->flush();
            $formatted = $serializer->normalize($CentreCamp,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" Promoinvalide") ;
    }
    /**
     * @Route("/updateCentreCampMobile", name="updateCentreCampMobile")
     */
    public function updateCentreCampMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $CentreCamp = $entityManager->getRepository(CentreCamp::class)->find($request->get("id"));
        $nom_centre=$request->query->get("nom_centre") ;
        $Description_centre=$request->query->get("Description_centre") ;
        $img_centre=$request->query->get("img_centre") ;
        $lieux=$request->query->get("lieux") ;
        $tlf_centre=$request->query->get("tlf_centre") ;
        $mail_centre=$request->query->get("mail_centre") ;
        $mdps_centre=$request->query->get("mdps_centre") ;
        $CentreCamp->setNomCentre($nom_centre) ;
        $CentreCamp->setDescriptionCentre($Description_centre) ;
        $CentreCamp->setImgCentre($img_centre) ;
        $CentreCamp->setLieux($lieux) ;
        $CentreCamp->setTlfCentre($tlf_centre) ;
        $CentreCamp->setMailCentre($mail_centre) ;
        $CentreCamp->setMdpsCentre($mdps_centre) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($CentreCamp);
        $entityManager->flush();

        $formatted = $serializer->normalize($CentreCamp,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
// promo
    /**
     * @Route("/displayPromoMobile", name="displayPromoMobile")
     */
    public function displayPromoMobile(Request $request, SerializerInterface $serializer): Response
    {
        $Promo = $this->getDoctrine()->getRepository(Promo::class)->findAll();
        $formatted = $serializer->normalize($Promo,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route ("/triPromoMobile", name="triPromoMobile")
     */
    public function triPromoMobile(Request $request, SerializerInterface $serializer){
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Promo::class) ;
        $reclamations=$repository->triStatusASC();
        $formatted = $serializer->normalize($reclamations,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;

    }
    /**
     * @Route("/addPromoMobile", name="addPromoMobile")
     */
    public function addPromoMobile(Request $request, SerializerInterface $serializer, \Swift_Mailer $Mailer): Response
    {

        $Promo = new Promo();
        $Nom_promo=$request->query->get("Nom_promo") ;
        $nv_prix=$request->query->get("nv_prix") ;
        $Description_promo=$request->query->get("Description_promo") ;
        $Promo->setNomPromo($Nom_promo) ;
        $Promo->setNvPrix($nv_prix) ;
        $Promo->setDescriptionPromo($Description_promo) ;
        $message= (new Swift_Message('nouveau Promo'))
            // on attribut l'epéditeur
            ->setFrom('slim.ayadi@esprit.tn')
            //on attribut le destinataire
            ->setTo('slim.ayadi@esprit.tn')
            // on crée le message la vue Twig
            ->setBody($this->renderView(
                'emails/Promo.html.twig'
            ), 'text/html'


            );

        ; //on envoie le message
        $Mailer->send($message);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Promo);
        $entityManager->flush();

        $formatted = $serializer->normalize($Promo,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deletePromoMobile", name="deletePromoMobile")
     */
    public function deletePromoMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $Promo = $entityManager->getRepository(Promo::class)->find($id);
        if($Promo!=null){
            $entityManager->remove($Promo);
            $entityManager->flush();
            $formatted = $serializer->normalize($Promo,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" Promoinvalide") ;
    }
    /**
     * @Route("/updatePromoMobile", name="updatePromoMobile")
     */
    public function updatePromoMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $Promo = $entityManager->getRepository(Promo::class)->find($request->get("id"));
        $Nom_promo=$request->query->get("Nom_promo") ;
        $nv_prix=$request->query->get("nv_prix") ;
        $Description_promo=$request->query->get("Description_promo") ;
        $Promo->setNomPromo($Nom_promo) ;
        $Promo->setNvPrix($nv_prix) ;
        $Promo->setDescriptionPromo($Description_promo) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Promo);
        $entityManager->flush();

        $formatted = $serializer->normalize($Promo,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    // *** Activite
    /**
     * @Route("/displayActiviteMobile", name="displayActiviteMobile")
     */
    public function displayActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {
        $Activite = $this->getDoctrine()->getRepository(Activite::class)->findAll();
        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }/**
     * @Route("/displayActiviteMobilebyid", name="displayActiviteMobilebyid")
     */
    public function displayActiviteMobilebyid(Request $request, SerializerInterface $serializer): Response
    {
       $id = $request->get('id');
        $Activite = $this->getDoctrine()->getRepository(Activite::class)->findBy(array('categorie'=>$id));
        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route ("/triupMobile", name="triupMobile")
     */
    public function orderStatusASC(Request $request, SerializerInterface $serializer){
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Activite::class) ;
        $order=2;
        $reclamations=$repository->triStatusASC();
        $formatted = $serializer->normalize($reclamations,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;

    }
    /**
     * @Route("/ChangeState", name="ChangeState")
     */
    public function ChangeState(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->get('id') ;
        $entityManager = $this->getDoctrine()->getManager();
        $activite = $entityManager->getRepository(Activite::class)->find($id) ;
        $activite->setStatut("Desactivate") ;
        $entityManager->flush();


        return new Response("changed to desactivate",200) ;

    }
    /**
     * @Route("/addActiviteMobile", name="addActiviteMobile")
     */
    public function addActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {

        $Activite = new Activite();
        $nom=$request->query->get("nom") ;
        $photo=$request->query->get("photo") ;
        $description=$request->query->get("description") ;
        $lieu=$request->query->get("lieu") ;
        $prix=$request->query->get("prix") ;
        $statut=$request->query->get("statut") ;
        $Activite->setNom($nom) ;
        $Activite->setPhoto($photo) ;
        $Activite->setDescription($description) ;
        $Activite->setLieu($lieu) ;
        $Activite->setPrix($prix) ;
        $categorie=$request->query->get("categorie") ;
        $entityManager = $this->getDoctrine()->getManager();
        $categ= $entityManager->getRepository(Categorie::class)->find($categorie);
        $Activite->setCategorie($categ) ;
        $Activite->setStatut($statut) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Activite);
        $entityManager->flush();

        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteActiviteMobile", name="deleteActiviteMobile")
     */
    public function deleteActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $Activite = $entityManager->getRepository(Activite::class)->find($id);
        if($Activite!=null){
            $entityManager->remove($Activite);
            $entityManager->flush();
            $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" categorie invalide") ;
    }
    /**
     * @Route("/updateActiviteMobile", name="updateActiviteMobile")
     */
    public function updateActiviteMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $Activite = $entityManager->getRepository(Activite::class)->find($request->get("id"));
        $nom=$request->query->get("nom") ;
        $photo=$request->query->get("photo") ;
        $description=$request->query->get("description") ;
        $lieu=$request->query->get("lieu") ;
        $prix=$request->query->get("prix") ;
        $statut=$request->query->get("statut") ;
        $Activite->setNom($nom) ;
        $Activite->setPhoto($photo) ;
        $Activite->setDescription($description) ;
        $Activite->setLieu($lieu) ;
        $Activite->setPrix($prix) ;
        $Activite->setStatut($statut) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Activite);
        $entityManager->flush();

        $formatted = $serializer->normalize($Activite,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    // categorie
    /**
     * @Route("/displaycategorieMobile", name="displaycategorieMobile")
     */
    public function displaycategorieMobile(Request $request, SerializerInterface $serializer): Response
    {
        $Categorie = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        $formatted = $serializer->normalize($Categorie,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addCategorieMobile", name="addCategorieMobile")
     */
    public function addCategorieMobile(Request $request, SerializerInterface $serializer): Response
    {

        $Categorie = new Categorie();
        $theme=$request->query->get("theme") ;
        $description=$request->query->get("description") ;

        $Categorie->setTheme($theme) ;
        $Categorie->setDescription($description) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Categorie);
        $entityManager->flush();

        $formatted = $serializer->normalize($Categorie,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteCategorieMobile", name="deleteCategorieMobile")
     */
    public function deleteCategorieMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $Categorie = $entityManager->getRepository(Categorie::class)->find($id);
        if($Categorie!=null){
            $entityManager->remove($Categorie);
            $entityManager->flush();
            $formatted = $serializer->normalize($Categorie,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" categorie invalide") ;
    }
    /**
     * @Route("/updateCategorieMobile", name="updateCategorieMobile")
     */
    public function updateCategorieMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $Categorie = $entityManager->getRepository(Categorie::class)->find($request->get("id"));
        $theme=$request->query->get("theme") ;
        $description=$request->query->get("description") ;
        $Categorie->setTheme($theme) ;
        $Categorie->setDescription($description) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Categorie);
        $entityManager->flush();

        $formatted = $serializer->normalize($Categorie,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    // commentaire
    /**
     * @Route("/displayCommentaireMobile", name="displayCommentaireMobile")
     */
    public function displayCommentaireMobile(Request $request, SerializerInterface $serializer): Response
    {
        $Commentaire = $this->getDoctrine()->getRepository(Commentaire::class)->findAll();
        $formatted = $serializer->normalize($Commentaire,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    /**
     * @Route("/addCommentaireMobile", name="addCommentaireMobile")
     */
    public function addCommentaireMobile(Request $request, SerializerInterface $serializer): Response
    {

        $Commentaire = new Commentaire();
        $Description=$request->query->get("Description") ;
        $censor = new CensorWords;
        $badwords = $censor->setDictionary('fr');
        $cleanedComment = $censor->censorString($Description);
        $Commentaire->setDescription($cleanedComment['clean']) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Commentaire);
        $entityManager->flush();

        $formatted = $serializer->normalize($Commentaire,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteCommentaireMobile", name="deleteCommentaireMobile")
     */
    public function deleteCommentaireMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $Commentaire = $entityManager->getRepository(Commentaire::class)->find($id);
        if($Commentaire!=null){
            $entityManager->remove($Commentaire);
            $entityManager->flush();
            $formatted = $serializer->normalize($Commentaire,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" categorie invalide") ;
    }
    /**
     * @Route("/updateCommentaireMobile", name="updateCommentaireMobile")
     */
    public function updateCommentaireMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $Commentaire = $entityManager->getRepository(Commentaire::class)->find($request->get("id"));
        $Description=$request->query->get("Description") ;
        $Commentaire->setDescription($Description) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Commentaire);
        $entityManager->flush();

        $formatted = $serializer->normalize($Commentaire,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
    // reclamation
    /**
     * @Route("/displayReclamationMobile", name="displayReclamationMobile")
     */
    public function displayReclamationMobile(Request $request, SerializerInterface $serializer): Response
    {
        $Reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $formatted = $serializer->normalize($Reclamation,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }

    /**
     * @Route("/addReclamationMobile", name="addReclamationMobile")
     */
    public function addReclamationMobile(Request $request, SerializerInterface $serializer): Response
    {

        $Reclamation = new Reclamation();
        $description=$request->query->get("description") ;
        $email=$request->get("email") ;
        $Reclamation->setDescription($description) ;
        $Reclamation->setEmail($email) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Reclamation);
        $entityManager->flush();

        $formatted = $serializer->normalize($Reclamation,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;



    }
    /**
     * @Route("/deleteReclamationMobile", name="deleteReclamationMobile")
     */
    public function deleteReclamationMobile(Request $request, SerializerInterface $serializer): Response
    {
        $id=$request->query->get("id") ;
        $entityManager = $this->getDoctrine()->getManager();
        $Reclamation = $entityManager->getRepository(Reclamation::class)->find($id);
        if($Reclamation!=null){
            $entityManager->remove($Reclamation);
            $entityManager->flush();
            $formatted = $serializer->normalize($Reclamation,'json',['groups' => 'post:read']);
            return new Response(json_encode($formatted)) ;

        }


        return new Response(" categorie invalide") ;
    }
    /**
     * @Route("/updateReclamationMobile", name="updateReclamationMobile")
     */
    public function updateReclamationMobile(Request $request, SerializerInterface $serializer): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $Reclamation = $entityManager->getRepository(Reclamation::class)->find($request->get("id"));
        $description=$request->query->get("description") ;
        $email=$request->query->get("email") ;
        $Reclamation->setDescription($description) ;
        $Reclamation->setEmail($email) ;
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($Reclamation);
        $entityManager->flush();

        $formatted = $serializer->normalize($Reclamation,'json',['groups' => 'post:read']);
        return new Response(json_encode($formatted)) ;
    }
}
