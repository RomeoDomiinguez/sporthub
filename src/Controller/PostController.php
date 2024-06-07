<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostController extends AbstractController
{
    private PostRepository $postRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/new_post', name: 'app_create_post')]
    public function create(Request $request)
    {
        $user = $this->getUser();

        if ($user === null) {
            throw new \LogicException('Debe estar autenticado para crear un nuevo post.');
        }

        $post = new Post();
        $post->setAutor($user);

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagenFile = $form['imagen']->getData();

            if ($imagenFile) {
                $nombreArchivo = uniqid().'.'.$imagenFile->guessExtension();

                try {
                    $imagenFile->move(
                        $this->getParameter('directorio_imagenes'), 
                        $nombreArchivo
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Se produjo un error al cargar la imagen.');
                    return $this->redirectToRoute('app_create_post');
                }

                $post->setImagen($nombreArchivo);
            }

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_feed');
        }

        $posts = $this->postRepository->findBy([], ['id' => 'DESC']);

        return $this->render('new_post.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }

    #[Route('/like_post/{id}', name: 'like_post', methods: ['POST'])]
    public function likePost(int $id): JsonResponse
    {
        $post = $this->postRepository->find($id);

        if (!$post) {
            return new JsonResponse(['success' => false, 'message' => 'Post not found'], 404);
        }

        $post->incrementLikes();
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return new JsonResponse(['success' => true, 'newLikeCount' => $post->getLikes()]);
    }
}
// 