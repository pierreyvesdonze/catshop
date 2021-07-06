<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartLine;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartController extends AbstractController
{
    private $em;
    private $session;
    private $articleRepository;

    public function __construct(
        TranslatorInterface $translator,
        EntityManagerInterface $em,
        SessionInterface $session,
        ArticleRepository $articleRepository
    ) {
        $this->translator = $translator;
        $this->em = $em;
        $this->session = $session;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @Route("/cart/show/{id}", name="cart_show", methods={"GET","POST"}, options={"expose"=true})
     */
    public function showCart() {

        $cart = $this->session->get('cart', []);

        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $cartWithData[] = [
                'article' => $this->articleRepository->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach ($cartWithData as $item) {
            $totalItem = $item['article']->getPrice() * $item['quantity'];
            $total += $totalItem;
        }


        return $this->render('cart/show.html.twig', [
            'items' => $cartWithData,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/add", name="add_to_cart", methods={"GET","POST"}, options={"expose"=true})
     */
    public function addToCart(
        Request $request
    ): JsonResponse {

        $user = $this->getUser();
        if ($request->isMethod('POST')) {

            $articleId = $request->getContent();
            $cart = $this->session->get('cart', []);
        
            if (!empty($cart[$articleId])) {
                $cart[$articleId]++;
            } else {
                $cart[$articleId] = 1;
            }

            $this->session->set('cart', $cart);

            return new JsonResponse($cart[$articleId]);
        }
    }

    /**
     * @Route("/cart/remove", name="cart_remove", methods={"GET","POST"}, options={"expose"=true})
     */
    public function removeFromCart(
        Request $request
    ) {
        if ($request->isMethod('POST')) {
            $cart = $this->session->get('cart', []);
            $articleId = $request->getContent();

            if (!empty($cart[$articleId])) {
                unset($cart[$articleId]);
            }
            
            $this->session->set('cart', $cart);

            return new JsonResponse('articleRemoved');
        }
    }

    /**
     * @Route("/cart/create/{id}", name="cart_create", methods={"GET","POST"}, options={"expose"=true})
     */
    public function cartCreate(
        User $user,
        CartRepository $cartRepository,
        ArticleRepository $articleRepository
    ) {

        $userCart = $cartRepository->findCurrentCart(false, $user);
        $tempCart = $this->session->get('cart');

        // Avoid to add multiple times same articles
        if (null !== $userCart) {

            $oldCartLines = $userCart->getCartLines();
            foreach ($oldCartLines as $cartline) {
                $userCart->removeCartLine($cartline);
            }
        } else {
            $userCart = new Cart;
            $userCart->setIsValid(false);
            $userCart->setUser($user);

            $this->em->persist($userCart);
        }

        $cartArray = [];
        foreach ($tempCart as $articleId => $quantity) {
            $articles = $articleRepository->findById($articleId);
            foreach ($articles as $article) {
                for ($i = 0; $i < $quantity; $i++) {
                    $cartArray[] = $article;
                    $newCartline = new CartLine;
                    $newCartline->setCart($userCart);
                    $newCartline->setArticle($article);

                    $this->em->persist($newCartline);
                }
            }
        }

        $this->em->flush();

        return $this->redirectToRoute('order_show', [
            'id' => $user->getId()
        ]);
    }
}
