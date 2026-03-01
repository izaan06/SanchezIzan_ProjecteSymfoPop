<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controlador per a la gestió de productes.
 */
#[Route('/product')]
class ProductController extends AbstractController
{
    /**
     * Llista tots els productes disponibles, ordenats per data de creació.
     */
    #[Route('', name: 'app_product_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Llista només els productes de l'usuari autenticat.
     */
    #[Route('/my/products', name: 'app_my_products', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function myProducts(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy(['owner' => $this->getUser()], ['createdAt' => 'DESC']);

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'title' => 'Els meus Productes',
            'show_actions' => true,
            'show_new_button' => true,
            'empty_message' => 'Encara no has publicat cap producte.',
        ]);
    }

    /**
     * Crea un nou producte. Assigna automàticament l'usuari actual com a propietari.
     */
    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product->setOwner($this->getUser());
            $product->setCreatedAt(new \DateTime());

            if (!$product->getImage()) {
                $product->setImage('https://picsum.photos/seed/' . uniqid() . '/600/400');
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Producte creat correctament!');

            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    /**
     * Mostra els detalls d'un producte específic.
     */
    #[Route('/{id}', name: 'app_product_show', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function show(Product $product): Response
    {
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Permet editar un producte existent. Valida que l'usuari en sigui el propietari.
     */
    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No pots editar aquest producte.');
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Producte actualitzat correctament!');

            return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
        }

        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ], new Response(null, $form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    /**
     * Elimina un producte. Requereix ser el propietari i validació del token CSRF.
     */
    #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        if ($product->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException('No pots esborrar aquest producte.');
        }

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($product);
            $entityManager->flush();
            $this->addFlash('info', 'Producte eliminat correctament.');
        }

        return $this->redirectToRoute('app_product_index');
    }
}
