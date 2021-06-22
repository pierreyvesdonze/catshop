<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="homepage")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBestSellers();

        return $this->render('main/homepage.html.twig', [
            'articles' => $articles
        ]);
    }
}
