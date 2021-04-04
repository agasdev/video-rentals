<?php

namespace App\Controller\Api;

use App\Service\CategoryManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;

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
}