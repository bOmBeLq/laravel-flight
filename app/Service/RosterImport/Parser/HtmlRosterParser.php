<?php

namespace App\Service\RosterImport\Parser;

use App\Models\Enum\ActivityType;
use App\Service\RosterImport\Parser\Helper\HtmlFileParser;
use Illuminate\Support\Collection;

class HtmlRosterParser implements RosterParserInterface
{
    public function parse(string $filePath): Collection
    {
        $htmlParser = new HtmlFileParser($filePath);

        $period = $this->getPeriod($htmlParser);

        $tableData = $htmlParser->parseTable('table.activityTableStyle');

        $baseDate = $period['from'];
        $lastDate = null;
        return collect($tableData)->map(function (array $data) use (&$lastDate, $baseDate) {
            $day = (int) filter_var($data['Date'], FILTER_SANITIZE_NUMBER_INT);
            if ($day) {
                if ($day == 1 && $lastDate) {
                    $baseDate = $baseDate->modify('+1 month');
                }
                $date = $baseDate->setDate($baseDate->format('Y'), $baseDate->format('m'), $day);
            } else {
                $date = $lastDate;
            }
            $row = new ParseResultRow(
                activity: $data['Activity'],
                type: $this->mapActivityType($data['Activity']),
                checkInTime: $this->createTime($date, $data['C/I(Z)']),
                checkOutTime: $this->createTime($date, $data['C/O(Z)']),
                locationFrom: $data['From'],
                locationTo: $data['To'],
                timeFrom: $this->createTime($date, $data['STD(Z)']),
                timeTo: $this->createTime($date, $data['STA(Z)']),
            );
            $lastDate = $date;
            return $row;
        });
    }

    private function createTime(\DateTimeImmutable $date, $timeString): ?\DateTimeImmutable
    {
        if (empty(trim($timeString, " \t\n\r\0\x0B\xC2\xA0"))) {
            return null;
        }
        $timeParts = str_split($timeString, 2);
        return $date->setTime($timeParts[0], $timeParts[1]);
    }

    /**
     * @return \DateTimeImmutable[]
     */
    private function getPeriod(HtmlFileParser $htmlFileParser): array
    {
        $period = $htmlFileParser->getCrawler()->filter('div.row.printOnly')->text();
        // eg. $period = 'Period: 10Jan22 to 23Jan22 (ILV - Jan de Bosman)'
        preg_match('/^Period: (?<from>[^\s]+) to (?<to>[^\s]+) /', $period, $match);

        return [
            'from' => new \DateTimeImmutable($match['from']),
            'to' => new \DateTimeImmutable($match['to'])
        ];
    }


    private function mapActivityType(string $originalActivity): ActivityType
    {
        if (str_starts_with($originalActivity, 'DX')) {
            return ActivityType::FLIGHT;
        }
        $map = [
            'OFF' => ActivityType::DAY_OFF,
            'SBY' => ActivityType::STAND_BY
        ];
        if (isset($map[$originalActivity])) {
            return $map[$originalActivity];
        }
        return ActivityType::UNKNOWN;
    }

    public static function getSupportedType(): string
    {
        return 'html';
    }
}
