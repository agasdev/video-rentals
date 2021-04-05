<?php

namespace App\Service;

use App\Entity\Dvd;
use App\Repository\DvdRepository;
use Doctrine\ORM\EntityManagerInterface;

class DvdManager
{
    private $entityManager;
    private $dvdRepository;

    public function __construct(EntityManagerInterface $entityManager, DvdRepository $dvdRepository)
    {
        $this->entityManager = $entityManager;
        $this->dvdRepository = $dvdRepository;
    }

    public function getRepository(): DvdRepository
    {
        return $this->dvdRepository;
    }

    public function find(int $id): ?Dvd
    {
        return $this->dvdRepository->find($id);
    }

    public function create(): Dvd
    {
        $oDvd = new Dvd();
        return $oDvd;
    }

    public function save(Dvd $oDvd): Dvd
    {
        $this->entityManager->persist($oDvd);
        $this->entityManager->flush();

        return $oDvd;
    }

    public function delete(Dvd $oDvd)
    {
        $this->entityManager->remove($oDvd);
        $this->entityManager->flush();
    }

    public function reload(Dvd $oDvd): Dvd
    {
        $this->entityManager->refresh($oDvd);

        return $oDvd;
    }
}