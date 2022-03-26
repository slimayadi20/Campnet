<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\Panier;


use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(): Response
    {
        return $this->render('panier/index.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }
    /**
     * @Route("/panier/add/{id}", name="afficherpanier")
     */
    public function afficherpanier(SessionInterface $session, ProduitRepository $productsRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;

        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite,
            ];
            $total += $product->getPrix() * $quantite;

//            $total += $product->getPrix() * $quantite;
        }

        return $this->render('/panier/affiche.html.twig', compact("dataPanier", "total"));
    }
    public function add(Produit $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getId();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }
        $p = new Panier();
        $p->setNom($product->getNom());
        $p->setQuantite($panier[$id]);
        $p->setPrix($product->getPrix());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($p);
        $entityManager->flush();
        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("affiche");
    }

}
