<?php

namespace App\Controller;

use App\Form\CheckoutType;
use App\Services\CartServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{

    private $cartServices;

    public function __construct(CartServices $cartServices)
    {
        $this->cartServices = $cartServices;
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function index( Request $request): Response
    {
        $user = $this->getUser();
        $cart = $this->cartServices->getFullCart();

        if(!isset($cart['products'])) {
            return $this->redirectToRoute("home");
        }

        if(!$user->getAddresses()->getValues()) {
            $this->addFlash('checkout_message', 'Please add an address to your account without continuing !');
            return $this->redirectToRoute("address_new");
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);

        return $this->render('checkout/index.html.twig', [
            'cart' => $cart,
            'checkout' => $form->createView()
        ]);
    }

    /**
     * @Route("/checkout/confirm", name="checkout_confirm")
     */
    public function confirm(Request $request): Response
    {
        $user = $this->getUser();
        $cart = $this->cartServices->getFullCart();

        if(!isset($cart['products'])) {
            $this->redirectToRoute("home");
        }

        if(!$user->getAddresses()->getValues()) {
            $this->addFlash('checkout_message', 'Please add an address to your account without continuing !');
            return $this->redirectToRoute("address_new");
        }

        $form = $this->createForm(CheckoutType::class, null, ['user' => $user]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $address = $data['address'];
            $carrier = $data['carrier'];
            $informations = $data['informations'];


            return $this->render('checkout/confirm.html.twig', [
                'cart' => $cart,
                'address' => $address,
                'carrier' => $carrier,
                'informations' => $informations,
                'checkout' => $form->createView()
            ]);
        }

        return $this->redirectToRoute("checkout");


    }
}
