<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\SortCatsType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/cats", name="articles")
     */
    public function cats(
        ArticleRepository $articleRepository,
        Request $request
    ): Response {
        $articles = $articleRepository->findAll();

        $form = $this->createForm(SortCatsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (1 === $form->get('price')->getData()) {
                $articles = $articleRepository->findByCheapest();
            } else {
                $articles = $articleRepository->findByExpensivest();
            }
        }

        return $this->render('article/cats.html.twig', [
            'articles' => $articles,
            'form' => $form->createView()
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
