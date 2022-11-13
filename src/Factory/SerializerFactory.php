<?php

namespace App\Factory;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class SerializerFactory
{
    public function makeApiSerializer(): Serializer {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        
        return new Serializer($normalizers, $encoders);
    }
}
