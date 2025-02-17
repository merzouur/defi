<?php

namespace App\Controller;

use App\Entity\Figurine;
use App\Form\FigurineType;
use App\Repository\CommentRepository;
use App\Repository\FigurineRepository;
use App\Repository\OeuvreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/figurine')]
final class FigurineController extends AbstractController
{
    #[Route(name: 'app_figurine_index', methods: ['GET'])]
    public function index(FigurineRepository $figurineRepository): Response
    {
        return $this->render('figurine/index.html.twig', [
            'figurines' => $figurineRepository->findAll(),
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
    public function show(Figurine $figurine, CommentRepository $commentRepository, OeuvreRepository $oeuvreRepository): Response
    {
        return $this->render('figurine/show.html.twig', [
            'figurine' => $figurine,
            'comments' =>$commentRepository->findBy(['figurine'=>$figurine]),
            'oeuvre' =>$oeuvreRepository->findBy(['figurine'=>$figurine])

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
        if ($this->isCsrfTokenValid('delete'.$figurine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($figurine);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_figurine_index', [], Response::HTTP_SEE_OTHER);
    }
}
