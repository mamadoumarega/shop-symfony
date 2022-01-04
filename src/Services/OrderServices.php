<?php

namespace App\Services;

use App\Entity\Cart;
use App\Entity\CartDetails;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class OrderServices
{
    private EntityManagerInterface $manager;
    private  ProductRepository $productRepository;

    public function __construct(EntityManagerInterface $manager,  ProductRepository $productRepository)
    {
        $this->manager = $manager;
        $this->productRepository = $productRepository;
    }

    public function createOrder(Cart $cart): Order
    {
        $order = new Order();
        $order->setReference($cart->getReference())
            ->setCarrierName($cart->getCarrierName())
            ->setCarrierPrice($cart->getCarrierPrice() / 100)
            ->setFullName($cart->getFullName())
            ->setDeliveryAddress($cart->getDeliveryAddress())
            ->setMoreInformations($cart->getMoreInformations())
            ->setQuantity($cart->getQuantity())
            ->setSubTotalHT($cart->getSubTotalHT() / 100)
            ->setTaxe($cart->getTaxe() / 100)
            ->setSubTotalTTC($cart->getSubTotalTTC() / 100)
            ->setUser($cart->getUser())
            ->setCreatedAt($cart->getCreatedAt())
        ;

        $this->manager->persist($order);

        $products = $cart->getCartDetails()->getValues();

        foreach ($products as $cart_product) {
            $orderDetails = new OrderDetails();
            $orderDetails->setOrders($order)
                        ->setProductName($cart_product->getProductName())
                        ->setProductPrice($cart_product->getProductPrice())
                        ->setQuantity($cart_product->getQuantity())
                        ->setSubTotalHT($cart_product->getSubTotalHT())
                        ->setSubTotalTTC($cart_product->getSubTotalTTC())
                        ->setTaxe($cart_product->getTaxe())
            ;

            $this->manager->persist($orderDetails);
        }

        $this->manager->flush();

        return $order;
    }

    public function saveCart($data, $user): string
    {
        $cart = new Cart();
        $reference = $this->generateUuid();
        $address = $data['checkout']['address'];
        $carrier = $data['checkout']['carrier'];
        $informations = $data['checkout']['informations'];

        $cart->setReference($reference)
            ->setCarrierName($carrier->getName())
            ->setCarrierPrice($carrier->getPrice()/100)
            ->setFullName($address->getFullName())
            ->setDeliveryAddress($address)
            ->setMoreInformations($informations)
            ->setQuantity($data['data']['quantity_cart'])
            ->setSubTotalHT($data['data']['subTotal'])
            ->setTaxe($data['data']['taxe'])
            ->setSubTotalTTC(round(($data['data']['subTotalTTC'] + $carrier->getPrice()/100), 2))
            ->setUser($user)
            ->setCreatedAt(new \DateTimeImmutable())
        ;

        $this->manager->persist($cart);

        $cart_details_array = [];

        foreach ($data['products'] as $products)
        {
            $cartDetails = new CartDetails();

            $subTotal = $products['quantity'] * $products['product']->getPrice()/100;

            $cartDetails->setCarts($cart)
                        ->setProductName($products['product']->getName())
                        ->setProductPrice($products['product']->getPrice()/100)
                        ->setQuantity($products['quantity'])
                        ->setSubTotalHT($subTotal)
                        ->setSubTotalTTC($subTotal * 1.2)
                        ->setTaxe($subTotal * 0.2)
            ;

            $this->manager->persist($cartDetails);
            $cart_details_array[] = $cartDetails;
        }


        $this->manager->flush();

        return $reference;
    }

    public function getLineItems(?Cart $cart): array
    {
        $cartDetails = $cart->getCartDetails();

        $line_items = [];

        foreach ($cartDetails as $details) {

           $product = $this->productRepository->findOneByName($details->getProductName());

            $line_items[] = [
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getName(),
                        'images' => [ $_ENV['YOUR_DOMAIN'].'/uploads/products/'.$product->getImage()],
                    ]
                ],
                'quantity' => $details->getQuantity(),
            ];
        }

        //Carrier
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $cart->getCarrierPrice(),
                'product_data' => [
                    'name' => 'Carrier ('.$cart->getCarrierName().')',
                ]
            ],
            'quantity' => 1,
        ];

        //Taxe
        $line_items[] = [
            'price_data' => [
                'currency' => 'usd',
                'unit_amount' => $cart->getTaxe(),
                'product_data' => [
                    'name' => 'TVA (20%)',
                ]
            ],
            'quantity' => 1,
        ];

        return $line_items;

    }

    public function generateUuid(): string
    {
        // Initialise le generateur de nombre aleatoires Mersenne Twister
        mt_srand((double)microtime()*100000);

        //str_ouper: renvoie la chaine en majiscules
        //uniqid génère un identifiant unique
        $chartid = strtoupper(md5(uniqid(rand(), true)));

        //Générer une chaine d'un octet à partir d'un nombre
        $hyphen = chr(45);

        //subs un segment de chaine
        return ""
            .substr($chartid, 0, 8).$hyphen
            .substr($chartid, 0, 8).$hyphen
            .substr($chartid, 0, 8).$hyphen
            .substr($chartid, 0, 8).$hyphen
            .substr($chartid, 0, 8)
        ;
    }




}
