<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Entity\Dvd;
use App\Form\Model\CategoryDto;
use App\Form\Model\DvdDto;
use App\Form\Type\DvdFormType;
use App\Repository\CategoryRepository;
use App\Repository\DvdRepository;
use App\Service\FileUploader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Flysystem\FilesystemException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * @Rest\Post(path="/dvd/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param DvdRepository $dvdRepository
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return Dvd|FormInterface|Response
     * @throws FilesystemException
     */
    public function editAction(
        int $id,
        EntityManagerInterface $entityManager,
        DvdRepository $dvdRepository,
        CategoryRepository $categoryRepository,
        Request $request,
        FileUploader $fileUploader
    )
    {
        $oDvd = $dvdRepository->find($id);
        if (!$oDvd) {
            throw $this->createNotFoundException('Dvd not found');
        }
        $dvdDto             = DvdDto::createFromDvd($oDvd);
        $originalCategories = new ArrayCollection();
        foreach ($oDvd->getCategories() as $category) {
            $categoryDto          = CategoryDto::createFromCategory($category);
            $dvdDto->categories[] = $categoryDto;
            $originalCategories->add($categoryDto);
        }

        $form = $this->createForm(DvdFormType::class, $dvdDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return new Response('', Response::HTTP_BAD_REQUEST);
        }

        if ($form->isValid()) {
            // Remove categories
            foreach ($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $dvdDto->categories)) {
                    $oCategory = $categoryRepository->find($originalCategoryDto->id);
                    $oDvd->removeCategory($oCategory);
                }
            }

            // Add categories
            foreach ($dvdDto->categories as $newCategoryDto) {
                if (!$originalCategories->contains($newCategoryDto)) {
                    $oCategory = $categoryRepository->find($newCategoryDto->id ?? 0);
                    if (!$oCategory) {
                        $oCategory = new Category();
                        $oCategory->setName($newCategoryDto->name);
                        $entityManager->persist($oCategory);
                    }
                    $oDvd->addCategory($oCategory);
                }
            }
            $oDvd->setTitle($dvdDto->title);
            if ($dvdDto->base64Image) {
                $filename = $fileUploader->uploadBase64File($dvdDto->base64Image);
                $oDvd->setImage($filename);
            }
            $entityManager->persist($oDvd);
            $entityManager->flush();
            $entityManager->refresh($oDvd);

            return $oDvd;
        }

        return $form;
    }
}