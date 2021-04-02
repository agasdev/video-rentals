<?php

namespace App\Controller\Api;

use App\Entity\Dvd;
use App\Form\DvdFormType;
use App\Repository\DvdRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return Dvd|FormInterface
     */
    public function postAction(EntityManagerInterface $entityManager, Request $request)
    {
        $oDvd = new Dvd();
        $form = $this->createForm(DvdFormType::class, $oDvd);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($oDvd);
            $entityManager->flush();

            return $oDvd;
        }

        return $form;
    }
}