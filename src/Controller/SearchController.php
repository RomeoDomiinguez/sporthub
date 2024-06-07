<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PostRepository;
use App\Entity\Post;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function search(Request $request, PostRepository $postRepository): Response
    {
        // Obtener parámetros de búsqueda del formulario
        $searchTerm = $request->query->get('search', '');
        $searchBy = $request->query->get('searchBy', 'all'); // 'all', 'title', 'category', 'author'

        // QueryBuilder para buscar en los posts
        $postsQuery = $postRepository->createQueryBuilder('p');

        // Aplicar filtros según los parámetros de búsqueda
        if (!empty($searchTerm)) {
            if ($searchBy === 'title' || $searchBy === 'all') {
                $postsQuery->orWhere('p.titulo LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }
            if ($searchBy === 'category' || $searchBy === 'all') {
                $postsQuery->orWhere('p.categoria LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }
            if ($searchBy === 'author' || $searchBy === 'all') {
                $postsQuery->join('p.autor', 'a')
                    ->orWhere('a.email LIKE :searchTerm')
                    ->setParameter('searchTerm', '%' . $searchTerm . '%');
            }
        }

        // Obtener los resultados de la búsqueda
        $posts = $postsQuery->orderBy('p.id', 'DESC')
            ->getQuery()
            ->getResult();

        return $this->render('results.html.twig', [
            'posts' => $posts,
            'searchTerm' => $searchTerm,
            'searchBy' => $searchBy,
        ]);
    }
}
