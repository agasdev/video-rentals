<?php

namespace App\Controller\Api;

use App\Entity\Dvd;
use App\Service\DvdFormProcessor;
use App\Service\DvdManager;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use League\Flysystem\FilesystemException;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DvdController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/dvds")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param DvdManager $dvdManager
     * @return array
     */
    public function getAction(DvdManager $dvdManager): array
    {
        return $dvdManager->getRepository()->findAll();
    }

    /**
     * @Rest\Post(path="/dvd")
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param DvdManager $dvdManager
     * @param DvdFormProcessor $dvdFormProcessor
     * @param Request $request
     * @return Dvd|FormInterface|mixed
     * @throws FilesystemException
     */
    public function postAction(
        DvdManager $dvdManager,
        DvdFormProcessor $dvdFormProcessor,
        Request $request
    ): View
    {
        $oDvd = $dvdManager->create();
        [$oDvd, $error] = ($dvdFormProcessor)($oDvd, $request);
        $statusCode = $oDvd ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $oDvd ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Post(path="/dvd/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param int $id
     * @param DvdFormProcessor $dvdFormProcessor
     * @param DvdManager $dvdManager
     * @param Request $request
     * @return View|mixed
     * @throws FilesystemException
     */
    public function editAction(
        int $id,
        DvdFormProcessor $dvdFormProcessor,
        DvdManager $dvdManager,
        Request $request

    ): View
    {
        $oDvd = $dvdManager->find($id);
        if (!$oDvd) {
            return View::create('Dvd not found', Response::HTTP_BAD_REQUEST);
        }
        [$oDvd, $error] = ($dvdFormProcessor)($oDvd, $request);
        $statusCode = $oDvd ? Response::HTTP_CREATED : Response::HTTP_BAD_REQUEST;
        $data = $oDvd ?? $error;

        return View::create($data, $statusCode);
    }

    /**
     * @Rest\Delete(path="/dvd/{id}", requirements={"id"="\d+"})
     * @Rest\View(serializerGroups={"dvd"}, serializerEnableMaxDepthChecks=true)
     * @param int $id
     * @param DvdManager $dvdManager
     * @return View|mixed
     */
    public function deleteAction(
        int $id,
        DvdManager $dvdManager
    ): View
    {
        $oDvd = $dvdManager->find($id);
        if (!$oDvd) {
            return View::create('Dvd not found', Response::HTTP_BAD_REQUEST);
        }
        $dvdManager->delete($oDvd);

        return View::create(null, Response::HTTP_NO_CONTENT);
    }
}