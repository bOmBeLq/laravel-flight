<?php

namespace App\Service\RosterImport\Parser\Helper;

use Symfony\Component\DomCrawler\Crawler;

class HtmlFileParser
{
    private Crawler $crawler;

    public function __construct(string $filePath)
    {
        $this->crawler = new Crawler(file_get_contents($filePath));
    }

    public function getCrawler(): Crawler
    {
        return $this->crawler;
    }

    /**
     * Will parse html table to php array
     * @param string $tablePath
     * @return array|string[][]
     */
    public function parseTable(string $tablePath): array
    {
        $table = $this->crawler->filter($tablePath);

        $headers = [];
        $table->filter('tr:first-child td')->each(function (Crawler $node) use (&$headers) {
            $headers[] = $node->text();
        });

        $resultData = [];
        $table->filter('tr:not(:first-child)')->each(function (Crawler $node) use ($headers, &$resultData) {
            $rowData = [];
            $node->filter('td')->each(function (Crawler $node) use (&$rowData) {
                $rowData[] = $node->text();
            });
            $resultData[] = array_combine($headers, $rowData);
        });
        return $resultData;
    }
}
