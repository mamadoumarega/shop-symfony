<?php

namespace App\Controller\Stripe;

use App\Entity\Cart;
use App\Entity\Order;
use App\Services\CartServices;
use App\Services\OrderServices;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCheckoutController extends AbstractController
{
    /**
     * @Route("/create-checkout-session/{reference}", name="create-checkout-session")
     * @throws ApiErrorException
     */
    public function index(?Cart $cart, OrderServices $orderServices, EntityManagerInterface $entityManager): Response
    {
        //$cart = $cartServices->getFullCart();
        $user = $this->getUser();

        if(!$cart){
            return $this->redirectToRoute("home");
        }

        $order = $orderServices->createOrder($cart);

       Stripe::setApiKey('sk_test_51KDzSxGmgFbl0jIigZgnGX0OScfCfuU4Fcv74HAV36ymU3GPhke2ttXOlSmYTpOOOVLThVQZ3ag4GtYZGVuut1n200nz03Vtml');

        $checkout_session = Session::create([
            'customer_email' => $user->getEmail(),
            'line_items' => $orderServices->getLineItems($cart),
            'mode' => 'payment',
            'success_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-success-payment/{CHECKOUT_SESSION_ID}',
            'cancel_url' => $_ENV['YOUR_DOMAIN'] . '/stripe-cancel-payment/{CHECKOUT_SESSION_ID}',
        ]);

        $order->setStripeCheckoutSessionId($checkout_session->id);

        $entityManager->flush();

        return $this->json(['id' => $checkout_session->url ]);
    }
}
