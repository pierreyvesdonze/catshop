<?php

namespace App\Controller;

use App\Entity\DeliveryAddress;
use App\Entity\User;
use App\Form\Type\DeliveryAddressType;
use App\Repository\ArticleRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    private $em;
    private $session;
    private $articleRepository;

    public function __construct(
        EntityManagerInterface $em,
        SessionInterface $session,
        ArticleRepository $articleRepository
    ) {
        $this->em = $em;
        $this->session = $session;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/order/show/{id}", name="order_show", methods={"GET","POST"}, options={"expose"=true})
     */
    public function orderShow(
        User $user,
        CartRepository $cartRepository,
        Request $request
    ) {

        $form = $this->createForm(DeliveryAddressType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newAddress = new DeliveryAddress;
            $newAddress->setUser($user);
            $newAddress->setAddressTitle($form->get('title')->getData());
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

        $userCart = $cartRepository->findCurrentCart(false, $user);

        return $this->render('cart/resume.html.twig', [
            'userCart' => $userCart,
            'form' => $form->createView()
        ]);
    }
}
