<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use App\Services\CartServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeSuccessPaymentController extends AbstractController
{
    /**
     * @Route("/stripe-success-payment/{stripeCheckoutSessionId}", name="stripe-success-payment")
     */
    public function index(?Order $order, CartServices $cartServices, EntityManagerInterface $manager): Response
    {
        if(!$order || $order->getUser() !== $this->getUser()) {
            $this->redirectToRoute("home");
        }

        if(!$order->getIsPaid()){
            // commande payée
            $order->setIsPaid(true);
            // mettre à jour la BDD
            $manager->flush();

            // vider le panier après avoir payer
            $cartServices->deleteCart();

            // mail
        }


        return $this->render('stripe/stripe_success_payment/index.html.twig', [
            'controller_name' => 'StripeSuccessPaymentController',
            'order' => $order
        ]);
    }
}
