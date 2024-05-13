<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
                        $this->getParameter('directorio_imagenes'), // Directorio configurado en services.yaml
                        $nombreArchivo
                    );
                } catch (FileException $e) {
                    // Manejar el error si la carga de la imagen falla
                    // Por ejemplo, podrías añadir un mensaje flash y redirigir a la página de creación de post
                    $this->addFlash('error', 'Se produjo un error al cargar la imagen.');
                    return $this->redirectToRoute('app_create_post');
                }

                $post->setImagen($nombreArchivo);
            }

            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_feed');
        }

        // Obtener todos los posts ordenados de forma inversa por su id
        $posts = $this->postRepository->findBy([], ['id' => 'DESC']);

        return $this->render('new_post.html.twig', [
            'form' => $form->createView(),
            'posts' => $posts,
        ]);
    }
}