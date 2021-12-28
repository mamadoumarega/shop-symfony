<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        $productBestSeller = $productRepository->findByIsBestSeller(1);

        $productSpecialOffer = $productRepository->findByIsSpecialOffer(1);

        $productNewArrival = $productRepository->findByIsNewArrival(1);

        $productFeatured = $productRepository->findByIsFeatured(1);

        //dd($products, $productBestSeller, $productFeatured, $productNewArrival, $productSpecialOffer);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'productBestSeller' => $productBestSeller,
            'productSpecialOffer' => $productSpecialOffer,
            'productNewArrival' => $productNewArrival,
            'productFeatured' => $productFeatured,
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_details")
     */
    public function show(?Product $product): Response
    {

        if (!$product) {
            return $this->redirectToRoute('home');
        }

        return $this->render('home/single_product.html.twig',[
            'product' => $product,
        ]);
    }
}
