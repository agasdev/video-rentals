<?php

namespace App\Service;

use App\Entity\Dvd;
use App\Form\Model\CategoryDto;
use App\Form\Model\DvdDto;
use App\Form\Type\DvdFormType;
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
        $dvdDto = DvdDto::createFromDvd($oDvd);
        foreach ($oDvd->getCategories() as $category) {
            $dvdDto->categories[] = CategoryDto::createFromCategory($category);
        }

        $form = $this->formFactory->create(DvdFormType::class, $dvdDto);
        $form->handleRequest($request);

        if (!$form->isSubmitted()) {
            return [null, 'Form is not submitted'];
        }

        if (!$form->isValid()) {
            return [null, $form];
        }

        $aCategories = [];
        foreach ($dvdDto->categories as $newCategoryDto) {
            $oCategory = $this->categoryManager->find($newCategoryDto->id ?? 0);
            if (!$oCategory) {
                $oCategory = $this->categoryManager->create();
                $oCategory->setName($newCategoryDto->name);
                $this->categoryManager->persist($oCategory);
            }
            $aCategories[] = $oCategory;
        }

        $filename = null;
        if ($dvdDto->base64Image) {
            $filename = $this->fileUploader->uploadBase64File($dvdDto->base64Image);

        }
        $oDvd->update($dvdDto->title, $filename, ...$aCategories);
        $this->dvdManager->save($oDvd);
        $this->dvdManager->reload($oDvd);

        return [$oDvd, null];
    }
}