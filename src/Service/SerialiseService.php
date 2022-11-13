<?php

namespace App\Service;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use App\Factory\SerializerFactory;

class SerialiseService
{
    protected $serializer;

    public function __construct(SerializerFactory $serializerFactory)
    {
        $this->serializer = $serializerFactory->makeApiSerializer();
    }

    public function convert($data, string $format)
    {
        $ignoredParams = ['__initializer__', '__cloner__', '__isInitialized__'];
        $ignoredParams = [AbstractNormalizer::IGNORED_ATTRIBUTES => $ignoredParams];
        
        return $this->serializer->serialize($data, $format, $ignoredParams);
    }
}
