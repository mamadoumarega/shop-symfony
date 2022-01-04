<?php

namespace App\Controller\Stripe;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeCancelPaymentController extends AbstractController
{
    /**
     * @Route("/stripe-cancel-payment/{stripeCheckoutSessionId}", name="stripe-cancel-payment")
     */
    public function index(?Order $order): Response
    {
        if(!$order || $order->getUser() !== $this->getUser()) {
            $this->redirectToRoute("home");
        }

        return $this->render('stripe/stripe_cancel_payment/index.html.twig', [
            'controller_name' => 'StripeCancelPaymentController',
            'order' => $order
        ]);
    }
}
