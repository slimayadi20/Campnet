<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Categorie;
use App\Entity\CentreCamp;
use App\Entity\Commentaire;
use App\Entity\Promo;
use App\Entity\Reclamation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class MobileController extends AbstractController
{
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
     * @Route("/addPromoMobile", name="addPromoMobile")
     */
    public function addPromoMobile(Request $request, SerializerInterface $serializer): Response
    {

        $Promo = new Promo();
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
        $Commentaire->setDescription($Description) ;
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
        $email=$request->query->get("email") ;
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
