<?php

namespace App\Service;

use App\Entity\Dvd;
use App\Form\Model\CategoryDto;
use App\Form\Model\DvdDto;
use App\Form\Type\DvdFormType;
use Doctrine\Common\Collections\ArrayCollection;
use League\Flysystem\FilesystemException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class DvdFormProcessor
{
    private $dvdManager;
    private $categoryManager;
    private $fileUploader;
    private $formFactory;

    public function __construct(
        DvdManager $dvdManager,
        CategoryManager $categoryManager,
        FileUploader $fileUploader,
        FormFactoryInterface $formFactory
    )
    {
        $this->dvdManager      = $dvdManager;
        $this->categoryManager = $categoryManager;
        $this->fileUploader    = $fileUploader;
        $this->formFactory     = $formFactory;
    }

    /**
     * @param Dvd $oDvd
     * @param Request $request
     * @return array
     * @throws FilesystemException
     */
    public function __invoke(Dvd $oDvd, Request $request): array
    {
        $dvdDto             = DvdDto::createFromDvd($oDvd);
        $originalCategories = new ArrayCollection();
        foreach ($oDvd->getCategories() as $category) {
            $categoryDto          = CategoryDto::createFromCategory($category);
            $dvdDto->categories[] = $categoryDto;
            $originalCategories->add($categoryDto);
        }

        $form = $this->formFactory->create(DvdFormType::class, $dvdDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if ($form->isValid()) {
            // Remove categories
            foreach ($originalCategories as $originalCategoryDto) {
                if (!in_array($originalCategoryDto, $dvdDto->categories)) {
                    $oCategory = $this->categoryManager->find($originalCategoryDto->id);
                    $oDvd->removeCategory($oCategory);
                }
            }

            // Add categories
            foreach ($dvdDto->categories as $newCategoryDto) {
                if (!$originalCategories->contains($newCategoryDto)) {
                    $oCategory = $this->categoryManager->find($newCategoryDto->id ?? 0);
                    if (!$oCategory) {
                        $oCategory = $this->categoryManager->create();
                        $oCategory->setName($newCategoryDto->name);
                        $this->categoryManager->persist($oCategory);
                    }
                    $oDvd->addCategory($oCategory);
                }
            }
            $oDvd->setTitle($dvdDto->title);
            if ($dvdDto->base64Image) {
                $filename = $this->fileUploader->uploadBase64File($dvdDto->base64Image);
                $oDvd->setImage($filename);
            }
            $this->dvdManager->save($oDvd);
            $this->dvdManager->reload($oDvd);

            return [$oDvd, null];
        }

        return [null, $form];
    }
}