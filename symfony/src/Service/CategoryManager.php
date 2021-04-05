<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryManager
{
    private $entityManager;
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        $this->entityManager      = $entityManager;
        $this->categoryRepository = $categoryRepository;
    }

    public function getRepository(): CategoryRepository
    {
        return $this->categoryRepository;
    }

    public function find(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function create(): Category
    {
        $oCategory = new Category();
        return $oCategory;
    }

    public function save(Category $oCategory): Category
    {
        $this->entityManager->persist($oCategory);
        $this->entityManager->flush();

        return $oCategory;
    }

    public function persist(Category $oCategory): Category
    {
        $this->entityManager->persist($oCategory);

        return $oCategory;
    }

    public function reload(Category $oCategory): Category
    {
        $this->entityManager->refresh($oCategory);

        return $oCategory;
    }
}