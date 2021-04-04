<?php

namespace App\Controller\Api;

use App\Entity\Dvd;
use App\Form\DvdFormType;
use App\Form\Model\DvdDto;
use App\Repository\DvdRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Flysystem\FilesystemException;
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
     * @Rest\Post(path="/dvd")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Dvd|FormInterface
     * @throws FilesystemException
     */
    public function postAction(
        EntityManagerInterface $entityManager,
        Request $request,
        FileUploader $fileUploader
    )
    {
        $oDvdDto = new DvdDto();
        $form    = $this->createForm(DvdFormType::class, $oDvdDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oDvd = new Dvd();
            $oDvd->setTitle($oDvdDto->title);
            if ($oDvdDto->base64Image) {
                $filename = $fileUploader->uploadBase64File($oDvdDto->base64Image);
                $oDvd->setImage($filename);
            }
            $entityManager->persist($oDvd);
            $entityManager->flush();

            return $oDvd;
        }

        return $form;
    }
}