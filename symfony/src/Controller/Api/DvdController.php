<?php

namespace App\Controller\Api;

use App\Entity\Dvd;
use App\Repository\DvdRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

class DvdController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/dvds")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param DvdRepository $dvdRepository
     * @return array
     */
    public function getAction(DvdRepository $dvdRepository): array
    {
        return $dvdRepository->findAll();
    }

    /**
     * @Rest\Post(path="/dvds")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param EntityManagerInterface $entityManager
     * @return Dvd
     */
    public function postAction(EntityManagerInterface $entityManager): Dvd
    {
        $oDvd = new Dvd();
        $oDvd->setTitle('Example Dvd 3');
        $entityManager->persist($oDvd);
        $entityManager->flush();

        return $oDvd;
    }
}