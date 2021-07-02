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
     * @Route("/order/create/{id}", name="order_create", methods={"GET","POST"}, options={"expose"=true})
     */
    public function orderCreate(
        User $user,
        CartRepository $cartRepository,
        ArticleRepository $articleRepository
    ) {

        $userCart = $cartRepository->findCurrentCart(false, $user);
        $tempCart = $this->session->get('cart');

        $cartArray = [];
        foreach ($tempCart as $articleId => $quantity) {
            $articles = $articleRepository->findById($articleId);
            foreach ($articles as $article) {
                $cartArray[] = $article;
                dump($quantity);
            }
        }

        dump($cartArray);
        dump($tempCart);

        return $this->render('cart/resume.html.twig', []);
    }
}
