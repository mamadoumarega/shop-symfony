<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataLoaderController extends AbstractController
{
    /**
     * @Route("/data", name="data_loader")
     */
    public function index(EntityManagerInterface $manager): Response
    {
        $file_product = dirname(__DIR__, 2) ."\product.json";
        $data_products = json_decode(file_get_contents($file_product), true);

        $file_categories = dirname(__DIR__, 2) ."\categories.json";
        $data_categories = json_decode(file_get_contents($file_categories), true);

        $categories = [];
        $products = [];

        //dd($data_products);

       foreach ($data_categories as $data_category) {
           $category = new Categories();
           $category->setName($data_category['name'])
                    ->setImage($data_category['image'])
           ;

           $manager->persist($category);
           $categories[] = $category;
       }

       foreach ($data_products as $data_product) {
           $product = new Product();
           $product->setName($data_product['name'])
                   ->setDescription($data_product['description'])
                   ->setIsBestSeller($data_product['is_best_seller'])
                   ->setPrice($data_product['price'])
                   ->setIsNewArrival($data_product['is_new_arrival'])
                   ->setIsFeatured($data_product['is_featured'])
                   ->setIsSpecialOffer($data_product['is_special_offer'])
                   ->setImage($data_product['image'])
                   ->setQuantity($data_product['quantity'])
                   ->setSlug($data_product['slug'])
           ;

           $manager->persist($product);
           $products[] = $product;

       }

       //$manager->flush();


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DataLoaderController.php',
        ]);
    }
}
