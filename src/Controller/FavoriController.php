<?php


namespace App\Controller;

use App\Entity\Favori;
use App\Entity\Figurine;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriController extends AbstractController
{

    #[Route('/favoris', name: 'app_favori_list', methods: ['GET'])]
    public function listFavoris(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer les favoris de l'utilisateur
        $favoris = $entityManager->getRepository(Favori::class)
            ->findBy(['user' => $user]);

        // Extraire les figurines des favoris
        $figurines = [];
        foreach ($favoris as $favori) {
            $figurines[] = $favori->getFigurine();
        }

        return $this->render('favori/list.html.twig', [
            'figurines' => $figurines,
        ]);
    }


    #[Route('/favori/{id}', name: 'app_favori_toggle', methods: ['POST'])]
    public function toggleFavori(Figurine $figurine, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $favori = $entityManager->getRepository(Favori::class)
            ->findOneBy(['user' => $user, 'figurine' => $figurine]);

        if ($favori) {
            $entityManager->remove($favori);
        } else {
            $favori = new Favori();
            $favori->setUser($user);
            $favori->setFigurine($figurine);
            $favori->setCreatedAt(new \DateTime());
            $entityManager->persist($favori);
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_figurine_show', ['id' => $figurine->getId()]);
    }
}
