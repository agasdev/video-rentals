<?php

namespace App\Form\Model;

use App\Entity\Category;

class CategoryDto
{
    public $id;
    public $name;

    public static function createFromCategory(Category $oCategory): self
    {
        $oDto       = new self();
        $oDto->id   = $oCategory->getId();
        $oDto->name = $oCategory->getName();

        return $oDto;
    }
}