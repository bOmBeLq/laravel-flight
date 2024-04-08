<?php

namespace App\Service\RosterImport\Parser;

use Psr\Container\ContainerInterface;

class RosterParserProvider
{
    /**
     * @var RosterParserInterface[]
     */
    private array $parsers = [
        HtmlRosterParser::class
    ];

    public function __construct(private readonly ContainerInterface $container)
    {

    }

    public function getParserByType(string $type): RosterParserInterface
    {
        foreach ($this->parsers as $parserClass) {
            if ($parserClass::getSupportedType() === $type) {
                return $this->container->get($parserClass);
            }
        }
    }

    public function hasParserForType(string $type)
    {
        foreach ($this->parsers as $parserClass) {
            if ($parserClass::getSupportedType() === $type) {
                return true;
            }
        }
    }
}
