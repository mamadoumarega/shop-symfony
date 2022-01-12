<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\SearchProduct;
use App\Form\SearchProductType;
use App\Repository\HomeSliderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ProductRepository $productRepository, HomeSliderRepository $sliderRepository): Response
    {
        $products = $productRepository->findAll();

        $homeSlider = $sliderRepository->findBy(['isDisplayed' => true]);


        $productBestSeller = $productRepository->findByIsBestSeller(1);
        //dd($productBestSeller);

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
            'homeSlider' => $homeSlider
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

    /**
     * @Route("/shop", name="shop")
     */
    public function shop(ProductRepository $productRepository, Request $request): Response
    {
        $products = $productRepository->findAll();

        $search = new SearchProduct();

        $form = $this->createForm(SearchProductType::class, $search);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $products = $productRepository->findWithSearch($search);
            //dd($search);
        }

        return $this->render('home/shop.html.twig', [
            'products' => $products,
            'search' => $form->createView()
        ]);
    }
}
