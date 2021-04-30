<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Article::class);
    }

    /**
     * @Route("/article/{id}", name="article_detail")
     * @param int $id
     * @return Response
     */
    public function detail(int $id): Response
    {
        //$article = $this->repository->find($id);
        $article = $this->repository->getPartialById($id);
        if (!$article instanceof Article) {
            throw new NotFoundHttpException();
        }

        return $this->render('article_detail.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/category/{categoryId}/articles/{limit}", name="articles_last_by_category")
     * @param int $categoryId
     * @param int $limit
     * @return Response
     */
    public function lastByCategory(int $categoryId, int $limit): Response
    {
        return $this->render('article_list.html.twig', [
            //'articles' => $this->repository->getLastByCategory($categoryId, $limit),
            'articles' => $this->repository->getLastPartialsByCategory($categoryId, $limit)
        ]);
    }

    /**
     * @Route("/articles/{limit}", name="articles_last_data", requirements={"limit"="\d+"})
     * @param int $limit
     * @return Response
     */
    public function lastData(int $limit): Response
    {
        return $this->render('articles_data.html.twig', [
            'articles' => $this->repository->getLastArticlesData($limit)
        ]);
    }

    /**
     * @Route("/articles/published", name="articles_published")
     * @return Response
     */
    public function published(): Response
    {
        return $this->render('article_list.html.twig', [
            'articles' => $this->repository->getPublished()
        ]);
    }
}
