<?php

namespace App\Controller;

use App\Entity\Figurine;
use App\Entity\Favori;
use App\Entity\Comment;
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
use App\Form\CommentType;

#[Route('/figurine')]
final class FigurineController extends AbstractController
{
    #[Route(name: 'app_figurine_index', methods: ['GET'])]
    public function index(FigurineRepository $figurineRepository, CategoryRepository $categoryRepository, Request $request): Response
    {
        
        $categoryId = $request->query->get('category');

        $categories = $categoryRepository->findAll();

        if ($categoryId) {
            $figurines = $figurineRepository->createQueryBuilder('f')
                ->join('f.oeuvre', 'o')
                ->where('o.category = :categoryId')
                ->setParameter('categoryId', $categoryId)
                ->getQuery()
                ->getResult();
        } else {
          
            $figurines = $figurineRepository->findAll();
        }

    
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
            $favoris = $entityManager->getRepository(Favori::class)->findBy(['user' => $user]);
        }

        // Créer un nouveau commentaire et le formulaire associé
        $comment = new Comment();
        $comment->setFigurine($figurine);
        $commentForm = $this->createForm(CommentType::class, $comment);

        // Passer les commentaires et le formulaire au template
        return $this->render('figurine/show.html.twig', [
            'figurine' => $figurine,
            'comments' => $commentRepository->findBy(['figurine' => $figurine]),
            'oeuvre' => $figurine->getOeuvre(),
            'favoris' => $favoris,
            'commentForm' => $commentForm->createView(),
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
