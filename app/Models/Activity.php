<?php

namespace App\Models;

use App\Http\Requests\ActivityListRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    public function getDates(): array
    {
        return ['created_at', 'updated_at', 'check_in_time', 'checkout_time', 'time_from', 'time_to'];
    }


    public static function filterByRequest(ActivityListRequest $request): Builder
    {
        $query = Activity::query();
        if ($request->getDateTimeFrom()) {
            $query->where('time_from', '>=', $request->getDateTimeFrom());
        }
        if ($request->getDateTimeTo()) {
            $query->where('time_to', '<=', $request->getDateTimeTo());
        }
        if ($request->isPeriodNextWeek()) {
            $query->where('time_from', '>=', Carbon::parse('monday next week'));
            $query->where('time_to', '<=', Carbon::parse('sunday next week')->setTime(23,59,59));
        }
        if($request->getType()) {
            $query->where('type', '=', $request->getType());
        }
        if($request->getLocationFrom()) {
            $query->where('location_from', '=', $request->getLocationFrom());
        }
        if($request->getLocationTo()) {
            $query->where('location_to', '=', $request->getLocationTo());
        }
        return $query;
    }
}
