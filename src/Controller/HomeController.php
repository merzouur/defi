<?php

// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository; // Importez le repository
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Récupérer toutes les catégories depuis le repository
        $categories = $this->categoryRepository->findAll();

        // Passer les catégories au template
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
        ]);
    }

    #[Route('/', name: 'root_redirect')]
    public function redirectToHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}

