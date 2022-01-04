<?php

namespace App\Controller\Account;

use App\Entity\Order;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    /**
     * @Route("/", name="account")
     */
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(['isPaid' => true, 'user' => $this->getUser()], ['id' => 'DESC']);
        //dd($orders);

        return $this->render('account/index.html.twig', [
            'orders' => $orders
        ]);
    }


    /**
     * @Route("/order/{id}", name="order_detail")
     */
    public function show(?Order $order): Response
    {
        if(!$order || $order->getUser() !== $this->getUser()) {
            $this->redirectToRoute("home");
        }

        if(!$order->getIsPaid()){
            $this->redirectToRoute("account");
        }

        return $this->render('account/detail_order.html.twig', [
            'orders' => $order
        ]);
    }


}
