<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityListRequest;
use App\Models\Activity;
use App\Service\RosterImport\RosterImporter;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

class ActivityController extends Controller
{

    public function list(ActivityListRequest $request)
    {
        return Activity::filterByRequest($request)->orderBy('timeFrom')->paginate(50);
    }


    public function uploadRoster(Request $request, RosterImporter $importer)
    {
        $validated = $request->validate([
            'roster' => 'required',
            'type' => 'required'
        ]);
        /** @var UploadedFile $roster */
        $roster = $validated['roster'];

        $importer->import($roster->getPathname(), $validated['type']);
        return ['status' => 'success'];
    }
}
