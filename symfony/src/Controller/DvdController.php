<?php

namespace App\Controller;

use App\Entity\Dvd;
use App\Repository\DvdRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DvdController extends AbstractController
{
    /**
     * @Route("/dvds", name="get_dvd")
     * @param DvdRepository $dvdRepository
     * @return JsonResponse
     */
    public function list(DvdRepository $dvdRepository): JsonResponse
    {
        $dvds  = $dvdRepository->findAll();
        $aDvds = [];
        foreach ($dvds as $oDvd) {
            $aDvds[] = [
                'id'    => $oDvd->getId(),
                'title' => $oDvd->getTitle(),
                'image' => $oDvd->getImage()
            ];
        }

        return new JsonResponse([
            'success' => true,
            'data'    => $aDvds
        ]);
    }

    /**
     * @Route("/dvd/create", name="dvd_create")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function createDvd(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $title = $request->get('title', null);
        if (empty($title)) {
            return new JsonResponse([
                'success' => false,
                'error'   => 'Title cannot be empty'
            ]);
        }

        $oDvd = new Dvd();
        $oDvd->setTitle($title);
        $entityManager->persist($oDvd);
        $entityManager->flush();

        return new JsonResponse([
            'success' => true,
            'data'    => [
                'id'    => $oDvd->getId(),
                'title' => $oDvd->getTitle()
            ]
        ]);
    }
}
