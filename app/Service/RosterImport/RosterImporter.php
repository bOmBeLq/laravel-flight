<?php

namespace App\Service\RosterImport;

use App\Models\Activity;
use App\Models\Enum\ActivityType;
use App\Service\RosterImport\Parser\ParseResultRow;
use App\Service\RosterImport\Parser\RosterParserInterface;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RosterImporter
{
    /**
     * @param RosterParserInterface[] $parsers
     */
    public function __construct(private readonly array $parsers)
    {
    }

    public function import(string $file, string $type): void
    {
        $rosterData = $this->getRosterData($file, $type);

        $activities = $rosterData
            ->map(function (ParseResultRow $row) {
                return [
                    'activity' => $row->getActivity(),
                    'type' => $row->getType(),
                    'check_in_time' => $row->getCheckInTime(),
                    'check_out_time' => $row->getCheckOutTime(),
                    'time_from' => $row->getTimeFrom(),
                    'time_to' => $row->getTimeTo(),
                    'location_from' => $row->getLocationFrom(),
                    'location_to' => $row->getLocationTo(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            });
        Activity::insert($activities->toArray());
    }

    /**
     * @return Collection<ParseResultRow>
     */
    private function getRosterData(string $file, string $type): Collection
    {
        foreach ($this->parsers as $parser) {
            if ($parser->supports($type)) {
                return $parser->parse($file);
            }
        }
        throw new InvalidRosterException('Unsupported roster type ' . $type);
    }
}
