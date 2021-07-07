<?php

namespace App\Controller;

use App\Entity\DeliveryAddress;
use App\Entity\Order;
use App\Entity\User;
use App\Form\Type\ChangeDeliveryAddressType;
use App\Form\Type\DeliveryAddressType;
use App\Form\Type\FakeCardType;
use App\Repository\CartRepository;
use App\Repository\DeliveryAddressRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $em;
    private $session;

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session
    ) {
        $this->em = $em;
        $this->session = $session;
    }

    /**
     * @Route("/order/show/{id}", name="order_show", methods={"GET","POST"})
     */
    public function orderShow(
        User $user,
        CartRepository $cartRepository,
        DeliveryAddressRepository $deliveryAddressRepository,
        Request $request
    ) {

        $userCart = $cartRepository->findCurrentCart(false, $user);

        $deliveryAddress = $deliveryAddressRepository->findOneById($user->getId());
        
        // Create new Delivery address
        $form = $this->createForm(DeliveryAddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newAddress = new DeliveryAddress;
            $newAddress->setUser($user);
            $newAddress->setAddressTitle($form->get('addressTitle')->getData());
            $newAddress->setFirstName($form->get('firstName')->getData());
            $newAddress->setLastName($form->get('lastName')->getData());
            $newAddress->setNumberStreet($form->get('numberStreet')->getData());
            $newAddress->setStreetName($form->get('streetName')->getData());
            $newAddress->setPostalCode($form->get('postalCode')->getData());
            $newAddress->setTown($form->get('town')->getData());

            $this->em->persist($newAddress);
            $this->em->flush();

            $this->addFlash('success', 'Delivery address has been added !');

            return $this->redirectToRoute('order_show', [
                'id' => $user->getId()
            ]);
        }

        // Update delivery address
        $changeAddressForm = $this->createForm(ChangeDeliveryAddressType::class);

        $changeAddressForm->handleRequest($request);
        
        if ($changeAddressForm->isSubmitted() && $changeAddressForm->isValid()) {

            $deliveryAddress = $changeAddressForm->get('deliveryAddress')->getData();
        }

        return $this->render('cart/resume.html.twig', [
            'userCart' => $userCart,
            'form' => $form->createView(),
            'changeAddressForm' => $changeAddressForm->createView(),
            'deliveryAddress' => $deliveryAddress
        ]);
    }

      /**
     * @Route("/order/create/{id}", name="order_create", methods={"GET","POST"})
     */
    public function orderCreate(
        ?DeliveryAddress $deliveryAddress,
        CartRepository $cartRepository,
        OrderRepository $orderRepository,
        Request $request
        )

    {
        $user = $this->getUser();
        $userCart = $cartRepository->findCurrentCart(false, $user);
        $order = $orderRepository->findByCurrentCart($userCart->getId());

        if (null == $order) {

            $order = new Order;
            $order->setUser($user);
            $order->setCart($userCart);
            $order->setIsPayed(false);
            $order->setCreatedAt(new \DateTime('now'));
            $order->setDeliveryAddress($deliveryAddress);
            
            $this->em->persist($order);
            $this->em->flush();
        }

        $form = $this->createForm(FakeCardType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userCart->setIsValid(true);
            $order->setIsPayed(true);

            $this->em->flush();

            // Clear Session
            $cart = $this->session->set('cart', []);

            return $this->redirectToRoute('order_confirm', [
                'id' => $order->getId()
            ]);
        }

        return $this->render('order/payment.html.twig', [
            'form' => $form->createView()
        ]);
    }

      /**
     * @Route("/order/confirm/{id}", name="order_confirm")
     */
    public function orderConfirm(Order $order)
    {
        return $this->render('order/confirm.html.twig', [
            'order' => $order
        ]);
    }

}
