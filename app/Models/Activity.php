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
        if ($request->dateTimeFrom) {
            $query->where('time_from', '>=', $request->dateTimeFrom);
        }
        if ($request->dateTimeTo) {
            $query->where('time_to', '<=', $request->dateTimeTo);
        }
        if ($request->isPeriodNextWeek()) {
            $query->where('time_from', '>=', Carbon::parse('monday next week'));
            $query->where('time_to', '<=', Carbon::parse('sunday next week')->setTime(23,59,59));
        }
        if($request->type) {
            $query->where('type', '=', $request->type);
        }
        if($request->locationFrom) {
            $query->where('location_from', '=', $request->locationFrom);
        }
        if($request->locationTo) {
            $query->where('location_to', '=', $request->locationTo);
        }
        return $query;
    }
}
