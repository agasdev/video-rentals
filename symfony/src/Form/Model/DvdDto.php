<?php

namespace App\Form\Model;

use App\Entity\Dvd;

class DvdDto
{
    public $title;
    public $base64Image;
    public $categories;

    public function __construct()
    {
        $this->categories = [];
    }

    public static function createFromDvd(Dvd $oDvd): self
    {
        $oDto = new self();
        $oDto->title = $oDvd->getTitle();

        return $oDto;
    }
}