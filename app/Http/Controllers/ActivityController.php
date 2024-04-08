<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityListRequest;
use App\Http\Requests\UploadRosterRequest;
use App\Models\Activity;
use App\Service\RosterImport\RosterImporter;

class ActivityController extends Controller
{

    public function list(ActivityListRequest $request)
    {
        return Activity::filterByRequest($request)->orderBy('timeFrom')->paginate(50);
    }


    public function uploadRoster(UploadRosterRequest $request, RosterImporter $importer)
    {
        $importer->import($request->getRoster()->getPathname(), $request->getType());

        return ['status' => 'success'];
    }
}
