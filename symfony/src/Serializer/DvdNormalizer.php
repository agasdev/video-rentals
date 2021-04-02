<?php

namespace App\Serializer;

use App\Entity\Dvd;
use ArrayObject;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class DvdNormalizer implements ContextAwareNormalizerInterface
{
    private $normalizer;
    private $urlHelper;
    private $parameterBag;

    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper, ParameterBagInterface $parameterBag)
    {
        $this->normalizer   = $normalizer;
        $this->urlHelper    = $urlHelper;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @param Dvd $oDvd
     * @param string|null $format
     * @param array $context
     * @return array|ArrayObject|bool|float|int|string|void|null
     * @throws ExceptionInterface
     */
    public function normalize($oDvd, string $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($oDvd, $format, $context);

        if (!empty($oDvd->getImage())) {
            $data['image'] = $this->urlHelper->getAbsoluteUrl(
                $this->parameterBag->get('path_images') . $oDvd->getImage()
            );
        }

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Dvd;
    }
}