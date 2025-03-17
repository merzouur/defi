<?php

namespace App\Service;

use App\Entity\Figurine;
use Doctrine\ORM\EntityManagerInterface;

class FigurineService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createFigurine(string $name, string $description): Figurine
    {
        $figurine = new Figurine();
        $figurine->setName($name);
        $figurine->setDescription($description);

        $this->entityManager->persist($figurine);
        $this->entityManager->flush();

        return $figurine;
    }

    public function getAllFigurines(): array
    {
        return $this->entityManager->getRepository(Figurine::class)->findAll();
    }

}
