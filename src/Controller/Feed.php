<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PostRepository;

class Feed extends AbstractController
{
    #[Route('/feed', name: 'app_feed')]
    public function feed(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ['id' => 'DESC']);
        $user = $this->getUser();  

        return $this->render('feed.html.twig', [
            'posts' => $posts,
            'user' => $user,
        ]);
    }
}
