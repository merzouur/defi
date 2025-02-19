<?php

namespace App\Controller;

use App\Entity\Figurine;
use App\Entity\Favori;
use App\Form\FigurineType;
use App\Repository\CommentRepository;
use App\Repository\FigurineRepository;
use App\Repository\OeuvreRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/figurine')]
final class FigurineController extends AbstractController
{
    #[Route(name: 'app_figurine_index', methods: ['GET'])]
    public function index(FigurineRepository $figurineRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        // Récupérer l'ID de la catégorie sélectionnée à partir de la requête
        $categoryId = $request->query->get('category');

        // Récupérer toutes les catégories pour le formulaire de sélection
        $categories = $categoryRepository->findAll();

        // Filtrer les figurines en fonction de la catégorie sélectionnée
        if ($categoryId) {
            $figurines = $figurineRepository->createQueryBuilder('f')
                ->join('f.oeuvre', 'o')
                ->where('o.category = :categoryId')
                ->setParameter('categoryId', $categoryId)
                ->getQuery()
                ->getResult();
        } else {
            // Si aucune catégorie n'est sélectionnée, récupérer toutes les figurines
            $figurines = $figurineRepository->findAll();
        }

        // Rendre la vue avec les figurines et les catégories
        return $this->render('figurine/index.html.twig', [
            'figurines' => $figurines,
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'app_figurine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $figurine = new Figurine();
        $form = $this->createForm(FigurineType::class, $figurine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($figurine);
            $entityManager->flush();

            return $this->redirectToRoute('app_figurine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('figurine/new.html.twig', [
            'figurine' => $figurine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_figurine_show', methods: ['GET'])]
    public function show(Figurine $figurine, CommentRepository $commentRepository, OeuvreRepository $oeuvreRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $favoris = [];

        if ($user) {
            $favoris = $entityManager->getRepository(Favori::class)
                ->findBy(['user' => $user]);
        }
        return $this->render('figurine/show.html.twig', [
            'figurine' => $figurine,
            'comments' => $commentRepository->findBy(['figurine' => $figurine]),
            'oeuvre' => $figurine->getOeuvre(),
            'favoris' => $favoris,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_figurine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Figurine $figurine, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FigurineType::class, $figurine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_figurine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('figurine/edit.html.twig', [
            'figurine' => $figurine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_figurine_delete', methods: ['POST'])]
    public function delete(Request $request, Figurine $figurine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $figurine->getId(), $request->request->get('_token'))) {
            $entityManager->remove($figurine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_figurine_index', [], Response::HTTP_SEE_OTHER);
    }
}
