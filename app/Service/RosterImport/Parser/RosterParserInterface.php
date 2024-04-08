<?php

namespace App\Service\RosterImport\Parser;


use Illuminate\Support\Collection;

interface RosterParserInterface
{
    /**
     * @param string $filePath
     * @return Collection<int, ParseResultRow>
     */
    public function parse(string $filePath): Collection;

    public function supports(string $type): bool;
}
