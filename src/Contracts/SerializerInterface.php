<?php

namespace Empiriq\Contracts;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializer;

interface SerializerInterface extends SymfonySerializer, NormalizerInterface, DenormalizerInterface, EncoderInterface, DecoderInterface
{
}
