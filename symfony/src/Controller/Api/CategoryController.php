<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Form\Model\CategoryDto;
use App\Form\Type\CategoryFormType;
use App\Service\CategoryManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/categories")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param CategoryManager $categoryManager
     * @return array
     */
    public function getAction(CategoryManager $categoryManager): array
    {
        return $categoryManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post (path="/categories")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param Request $request
     * @param CategoryManager $categoryManager
     * @return Category|FormInterface
     */
    public function postAction(Request $request, CategoryManager $categoryManager)
    {
        $categoryDto = new CategoryDto();
        $form        = $this->createForm(CategoryFormType::class, $categoryDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $oCategory = $categoryManager->create();
            $oCategory->setName($categoryDto->name);
            $categoryManager->save($oCategory);

            return $oCategory;
        }

        return $form;
    }
}