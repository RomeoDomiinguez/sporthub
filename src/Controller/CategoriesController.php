<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class CategoriesController extends AbstractController
{
    #[Route('/groups', name: 'app_categories')]
    public function index(PostRepository $postRepository): Response
    {
        $categories = $postRepository->findUniqueCategoriesWithCount();

        return $this->render('categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/groups/{category}', name: 'app_category_posts')]
    public function showCategoryPosts(PostRepository $postRepository, string $category): Response
    {
        $posts = $postRepository->findByCategory($category);

        return $this->render('categories/posts.html.twig', [
            'category' => $category,
            'posts' => $posts,
        ]);
    }
}
