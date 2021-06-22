<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/cats/show", name="articles")
     */
    public function cats(ArticleRepository $articleRepository): Response
    {
        $cats = $articleRepository->findAll();

        return $this->render('article/cats.html.twig', [
            'cats' => $cats,
        ]);
    }

    /**
     * @Route("/cat/show/{id}", name="article_show")
     */
    public function show(Article $article): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
