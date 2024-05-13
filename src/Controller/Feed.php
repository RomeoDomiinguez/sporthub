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
        // Recuperar todos los posts ordenados por fecha de creación de manera descendente
        $posts = $postRepository->findBy([], ['id' => 'DESC']);
        // Obtener al usuario autenticado
        $user = $this->getUser();  

        return $this->render('feed.html.twig', [
            'posts' => $posts,
            'user' => $user,
        ]);
    }
}
