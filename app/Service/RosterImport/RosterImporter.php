<?php

namespace App\Service\RosterImport;

use App\Models\Activity;
use App\Models\Enum\ActivityType;
use App\Service\RosterImport\Parser\ParseResultRow;
use App\Service\RosterImport\Parser\RosterParserInterface;
use App\Service\RosterImport\Parser\RosterParserProvider;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RosterImporter
{
    public function __construct(private readonly RosterParserProvider $rosterParserProvider)
    {
    }

    public function import(string $file, string $type): void
    {
        $rosterData = $this->rosterParserProvider->getParserByType($type)->parse($file);

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
}
