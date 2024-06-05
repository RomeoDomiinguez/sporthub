<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    private PostRepository $postRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Debe estar autenticado para ver esta página.');
        }

        $posts = $this->postRepository->findBy(['autor' => $user], ['id' => 'DESC']);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
        ]);
    }

    #[Route('/profile/delete_post/{id}', name: 'app_delete_post', methods: ['POST'])]
    public function deletePost(int $id, Request $request): Response
    {
        $post = $this->postRepository->find($id);

        if (!$post || $post->getAutor() !== $this->getUser()) {
            throw $this->createNotFoundException('El post no existe o no tiene permiso para eliminarlo.');
        }

        $this->entityManager->remove($post);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_profile');
    }
}
