<?php

namespace App\Services;


use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartServices {

    private SessionInterface $session;
    private ProductRepository $repoProduct;
    private float $tva = 0.2;


    public function __construct(SessionInterface $session, ProductRepository $repoProduct)
    {
        $this->session = $session;
        $this->repoProduct = $repoProduct;
    }

    public function addToCart($id)
    {
        $cart = $this->getCart();

        if(isset($cart[$id])) {
            // le produit existe déjà
            $cart[$id]++;
        } else {
            //le produit n'existe pas
            $cart[$id] = 1;
        }

        $this->updateCart($cart);

    }

    public function deleteFromCart($id)
    {
        $cart = $this->getCart();


        if(isset($cart[$id])) {
            // produit déjà dans le panier
            if ($cart[$id] > 1) {
                //produit existe plus d'une fois
                $cart[$id]--;
            }else {
                unset($cart[$id]);
            }
            $this->updateCart($cart);
        }

    }

    public function deleteAllToCart($id)
    {
        $cart = $this->getCart();

        if(isset($cart[$id])) {
            // produit déjà dans le panier
            unset($cart[$id]);
            $this->updateCart($cart);
        }
    }

    public function deleteCart()
    {
        $this->updateCart([]);
    }

    public function updateCart($cart)
    {
        $this->session->set("cart", $cart);
        $this->session->set("cartData", $this->getFullCart());
    }

    public function getCart()
    {
      return $this->session->get("cart", []);
    }

    public function getFullCart(): array
    {
        $cart = $this->getCart();

        $fullCart = [];
        $quantity_cart = 0;
        $subTotal = 0;

        foreach ($cart as $id => $quantity) {
            $product = $this->repoProduct->find($id);

            if($product) {
                // produit récupéré
                $fullCart["products"][] = [
                      "quantity" => $quantity,
                      "product" => $product
                ];
                $quantity_cart += $quantity;
                $subTotal += $quantity * $product->getPrice() / 100;
            } else {
                // id incorrect
                $this->deleteFromCart($id);
            }
        }

        $fullCart["data"] = [
            "quantity_cart" => $quantity_cart,
            "subTotal" => $subTotal,
            "taxe"  => round($subTotal * $this->tva, 2),
            "subTotalTTC" => round(($subTotal + ($subTotal*$this->tva)), 2)
        ];

        return $fullCart;
    }

}
