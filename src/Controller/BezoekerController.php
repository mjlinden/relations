<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class BezoekerController extends AbstractController
{

    #[Route('/products', name: 'app_products')]
    public function index2(ProductRepository $p): Response
    {
        $products=$p->findAll();

        return $this->render('bezoeker/index.html.twig', [
            'controller_name' => 'BezoekerController',
            'products' => $products
        ]);
    }

    #[Route('/categories', name: 'app_categories')]
    public function index3(CategoryRepository $p): Response
    {
        $categories=$p->findAll();

        return $this->render('bezoeker/categories.html.twig', [
            'controller_name' => 'BezoekerController',
            'categories' => $categories
        ]);
    }

    #[Route('/insertcat', name: 'app_insert')]
    public function index4(Request $request,ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $category=new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $category = $form->getData();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($category);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();


// add flash messages
            $name=$category->getName();


            $this->addFlash(
                'notice',
                "Rij  $name toegevoegd aan category tabel"
            );


            return $this->redirectToRoute('app_categories');
        }
        return $this->renderForm('bezoeker/new.html.twig', [
            'form' => $form,
        ]);

    }


}
