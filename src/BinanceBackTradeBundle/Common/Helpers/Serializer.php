<?php

namespace Empiriq\BinanceBackTradeBundle\Common\Helpers;

use Empiriq\Contracts\SerializerInterface;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\LoaderChain;
use Symfony\Component\Serializer\Mapping\Loader\YamlFileLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\Serializer as SymfonySerializer;

class Serializer extends SymfonySerializer implements SerializerInterface
{
    public function __construct(
        string $mappingDirectory = __DIR__ . '/../../Resources/mapping',
    ) {
        $classMetadataFactory = new ClassMetadataFactory(
            new LoaderChain(
                array_map(
                    fn(SplFileInfo $file) => new YamlFileLoader($file->getRealPath()),
                    array_filter(
                        iterator_to_array(
                            new RecursiveIteratorIterator(
                                new RecursiveDirectoryIterator($mappingDirectory)
                            )
                        ),
                        fn(SplFileInfo $file) => $file->getExtension() === 'yaml'
                    )
                )
            )
        );
        parent::__construct(
            [
                new ArrayDenormalizer(),
                new UnwrappingDenormalizer(),
                new BackedEnumNormalizer(),
                new ObjectNormalizer(
                    classMetadataFactory: $classMetadataFactory,
                    nameConverter: new MetadataAwareNameConverter($classMetadataFactory),
                    propertyTypeExtractor: new PropertyInfoExtractor(
                        typeExtractors: [
                            new PhpDocExtractor(),
                        ]
                    ),
                    defaultContext: [
                        // Do not enforce PHP types strictly during denormalization
                        AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
                        // Skip null values when serializing objects to JSON/array
                        AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                    ]
                ),
            ],
            [
                new JsonEncoder(),
            ],
        );
    }
}
