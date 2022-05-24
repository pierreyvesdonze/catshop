<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class DefaultController extends AbstractController
{
    private $session;

    public function __construct(
        SessionInterface $session,
    ) {

        $this->session = $session;
    }

    /**
     * @Route("/", name="homepage")
     */
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $articles = $articleRepository->findBestSellers();    
        dump($cart = $this->session->get('cart', []));

        return $this->render('main/homepage.html.twig', [
            'articles' => $articles
        ]);
    }
}
