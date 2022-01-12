<?php

namespace App\Services;


use App\Entity\Order;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class StockManagerServices {

    private EntityManagerInterface $manager;
    private ProductRepository $repoProduct;

    public function __construct(EntityManagerInterface $manager, ProductRepository $repoProduct)
    {
        $this->manager = $manager;
        $this->repoProduct = $repoProduct;
    }

    public function deStock(Order $order)
    {
        $orderDetails = $order->getOrderDetails()->getValues();

        foreach ($orderDetails as $key => $details) {
            $product = $this->repoProduct->findByName($details->getProductName())[0];
            //dd($product);
            $newQuantity = $product->getQuantity() - $details->getQuantity();
            $product->setQuantity($newQuantity);

            $this->manager->flush();
        }
    }

}
