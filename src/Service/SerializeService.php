<?php

namespace App\Service;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializeService
{
    protected $serializer;

    public function __construct()
    {
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    public function convert($data, $format = 'json')
    {
        $ignoredParams = ['__initializer__', '__cloner__', '__isInitialized__'];
        $ignoredParams = [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredParams];
        
        return $this->serializer->serialize($data, $format, $ignoredParams);
    }
}
