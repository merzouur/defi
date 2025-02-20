<?php

// src/Controller/HomeController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Figurine; // Assurez-vous d'importer l'entité Figurine
use App\Repository\CategoryRepository;
use App\Repository\FigurineRepository; // Importez le repository des figurines
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $categoryRepository;
    private $figurineRepository;

    public function __construct(CategoryRepository $categoryRepository, FigurineRepository $figurineRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->figurineRepository = $figurineRepository;
    }

    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // Récupérer toutes les catégories depuis le repository
        $categories = $this->categoryRepository->findAll();

        // Récupérer les deux dernières figurines enregistrées
        $latestFigurines = $this->figurineRepository->findBy([], ['id' => 'DESC'], 2);

        // Passer les catégories et les figurines au template
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            'latestFigurines' => $latestFigurines,
        ]);
    }

    #[Route('/', name: 'root_redirect')]
    public function redirectToHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }
}
