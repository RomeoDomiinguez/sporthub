<?php

namespace App\Controller;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Form\PagoType;

class TiendaController extends AbstractController
{
    #[Route('/store', name: 'app_tienda')]
    public function index(ProductoRepository $productoRepository): Response
    {
        $productos = $productoRepository->findAll();
        return $this->render('tienda/index.html.twig', [
            'productos' => $productos,
        ]);
    }

    #[Route('/tienda/{id}/añadir_al_carrito', name: 'añadir_al_carrito', methods: ['POST'])]
    public function añadirAlCarrito(Request $request, Producto $producto, SessionInterface $session): Response
    {
        $carrito = $session->get('carrito', []);
        $id = $producto->getId();

        if (isset($carrito[$id])) {
            $carrito[$id]['cantidad']++;
        } else {
            $carrito[$id] = [
                'producto' => $producto,
                'cantidad' => 1,
            ];
        }

        $session->set('carrito', $carrito);

        return $this->redirectToRoute('app_tienda');
    }

    #[Route('/carrito', name: 'ver_carrito')]
    public function verCarrito(SessionInterface $session): Response
    {
        $carrito = $session->get('carrito', []);

        return $this->render('tienda/carrito.html.twig', [
            'carrito' => $carrito,
        ]);
    }

    #[Route('/carrito/eliminar/{id}', name: 'eliminar_del_carrito')]
    public function eliminarDelCarrito($id, SessionInterface $session): Response
    {
        $carrito = $session->get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
        }

        $session->set('carrito', $carrito);

        return $this->redirectToRoute('ver_carrito');
    }

    #[Route('/pago', name: 'pago')]
    public function pago(Request $request, SessionInterface $session): Response
    {
        $carrito = $session->get('carrito', []);

        if (empty($carrito)) {
            return $this->redirectToRoute('ver_carrito');
        }

        $form = $this->createForm(PagoType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $session->set('carrito', []);

            return $this->redirectToRoute('pago_exito');
        }

        return $this->render('tienda/pago.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/pago/exito', name: 'pago_exito')]
    public function pagoExito(): Response
    {
        return $this->render('tienda/pago_exito.html.twig');
    }
}
