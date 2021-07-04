<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        CartRepository $cartRepository
    ) {
        $userCart = $cartRepository->findCurrentCart(false, $user);
        
        return $this->render('cart/resume.html.twig', [
            'userCart' => $userCart
        ]);
    }
}
