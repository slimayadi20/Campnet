<?php

namespace App\Controller;
use App\Entity\Commande;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use ContainerHuQLa9m\PaginatorInterface_82dac15;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Element;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use function PHPUnit\Framework\assertNotNull;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produittttt", name="produit")
     */
    public function index(Request $request): Response
    {
//        public function index(Request $request, PaginatorInterface_82dac15 $paginator): Response
//    {
//        $donnees = $this->getDoctrine()->getRepository(Produit::class)->findBy([],
//        ['created_at' => 'desc']);
//        $produit = $paginator->paginate(
//            $donnees,
//            $request->query->getInt('page',1),
//2
//        );

        return $this->render('produit/index.html.twig', [
            'controller_name' => 'ProduitController',
            'form'=> $form->createView(),
        ]);
    }
    /**
     * @Route("/search", name="recherche")
     */
    public function searchAction(Request $request){
     $em= $this->getDoctrine()->getManager();
     $requestString = $request->get('q');
     $produit = $em->getRepository('App:Produit')->findEntitiesByString($requestString);
     if (!$produit){
         $result['produit']['error'] = "Produit not found :( "; }
     else {
         $result['produit']=$this->getRealEntities($produit);
     }
     return new Response(json_encode($result));

    }

    public function getRealEntities($produit)
    {
        foreach ($produit as $produit ){
            $realEntities[$produit->getId()] = [$produit->getNom(),$produit->getDescription(),$produit->getPrix()];
        }
        return $realEntities;
    }

    public function indexx()
    {
        $spreadsheet = new Spreadsheet();

        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello World !');
        $sheet->setTitle("Liste de produits");

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new Xlsx($spreadsheet);

        // In this case, we want to write the file in the public directory
        $publicDirectory = $this->get('kernel')->getProjectDir() . '/public';
        // e.g /var/www/project/public/my_first_excel_symfony4.xlsx
        $excelFilepath =  $publicDirectory . '/my_first_excel_symfony4.xlsx';

        // Create the file
        $writer->save($excelFilepath);

        // Return a text response to the browser saying that the excel was succesfully created
        return new Response("Excel generated succesfully");
    }


    /**
     * @Route("/Affiche/{id}", name="produit_show")
     */
    public function show($id ,ProduitRepository $produitRepository)
    {
        $produit=$produitRepository->find($id);
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }


    /**
     * @Route("/Afficheback/{id}", name="produit_showback")
     */
    public function showback($id ,ProduitRepository $produitRepository)
    {
        $produit=$produitRepository->find($id);
        return $this->render('produit/showback.html.twig', [
            'produit' => $produit,
        ]);
    }
    /**
     * @param ProduitRepository $repository
     * @return Response
     * @route("/produit" ,name="afficheproduit")
     */
    function Affiche(ProduitRepository $repository) //meme chose que metre 33 ligne
    {
        //$repo=$this->getDoctrine()->getRepository(ClassroomRepository::class);
        $produit=$repository->findAll();
        return $this->render('produit/affiche.html.twig',['c'=>$produit]);

    }

    /**
     * @param ProduitRepository $repository
     * @return Response
     * @route("/produitback" ,name="afficheproduitback")
     */
    function Afficheback(ProduitRepository $repository) //meme chose que metre 33 ligne
    {
        //$repo=$this->getDoctrine()->getRepository(ClassroomRepository::class);
        $produit=$repository->findAll();
        return $this->render('produit/afficheback.html.twig',['c'=>$produit]);

    }
    /**
     * @Route("/afficherpanier", name="afficherpanier")
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
        }

        return $this->render('/panier/affiche.html.twig', compact("dataPanier", "total"));
    }
    /**
     * @param Request $request
     * @param Response
     * @Route ("/new", name="ajouterproduit")
     */
    function Ajout(Request $request){
        $produit=new Produit();
       // $form=$this->createForm(ProduitType::class,$produit);
$form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
       // $form->add('submit',SubmitType::class);

        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('afficheproduit');

        }
        return $this->render('produit/new.html.twig',['form'=>$form->createView()]);

    }

    /**
     * @param Request $request
     * @param Response
     * @Route ("/newback", name="ajouterproduitback")
     */
    function Ajoutback(Request $request){
        $produit=new Produit();
        // $form=$this->createForm(ProduitType::class,$produit);

        $form=$this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        // $form->add('submit',SubmitType::class);

        if($form->isSubmitted()&&$form->isValid())
        {
            $em=$this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('afficheproduitback');

        }
        return $this->render('produit/newback.html.twig',['form'=>$form->createView()]);

    }

    /**
     * @param Request $request
     * @param int $id
     * @param ProduitRepository $repository
     * @Route ("/modifierproduitback/{id}",name="modifierproduitback")
     */
    public function modify(Request $request, int $id, ProduitRepository $repository)
    {
        //$entityManager = $this->getDoctrine()->getManager();
        $produit = $repository->find($id);
        //$classroom = $entityManager->getRepository(Classroom::class)->find($id);
        $form = $this->createForm(ProduitType::class, $produit);
        $form->add('nom');
        $form->add('description');
        $form->add('prix');
        $form->add('disponibilte');
        $form->add('photo');




        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
            return $this->redirectToRoute('afficheproduitback');


        }
        return $this->render("produit/modifierback.html.twig", [
            "fo" => $form->createView(),
        ]);}
    /**
     * @param $id
     * @param ProduitRepository $rep
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route ("/supprimerproduitback/{id}", name="supprimerproduitback")
     */
    function Delete($id, ProduitRepository $rep)
    {
        $produit = $rep->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('afficheproduitback');

    }

    /**
     * @Route("/panier/add/{id}", name="add_cart")
     */
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

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("afficheproduit");
    }/**
 * @Route("/panier/addpanier/{id}", name="add_panier")
 */
    public function add_panier(Produit $product, SessionInterface $session)
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

        return $this->redirectToRoute("afficherpanier");
    }
    /**
     * @Route("/supprimer/{id}", name="cart_remove")
     */
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier', []);

        if (empty($panier[$id])) {

            unset($panier[$id]);

        }
        else  {
            $panier[$id]--;
        }


        $session->set('panier', $panier);

        return $this->redirectToRoute('afficherpanier');
    }

}


