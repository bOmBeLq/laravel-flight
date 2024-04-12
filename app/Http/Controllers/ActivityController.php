<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActivityListRequest;
use App\Http\Requests\UploadRosterRequest;
use App\Models\Activity;
use App\Service\RosterImport\RosterImporter;
use OpenApi\Attributes as OA;

class ActivityController extends Controller
{
    #[OA\Get(
        path: "/api/activities",
        tags: ["Activities"],
        parameters: [
            new OA\Parameter(name: "dateTimeFrom", in: "query", schema: new OA\Schema(type: "dateTime", format: 'Y-m-d H:i:s', example: "2022-01-14 08:00:00")),
            new OA\Parameter(name: "dateTimeTo", in: "query", schema: new OA\Schema(type: "dateTime", format: 'Y-m-d H:i:s', example: "2022-01-20 08:00:00")),
            new OA\Parameter(name: "type", in: "query", schema: new OA\Schema(type: "string", enum: ["DAY_OFF", "STAND_BY", "FLIGHT", "UNKNOWN"])),
            new OA\Parameter(name: "period", in: "query", schema: new OA\Schema(type: "string", enum: ["nextWeek"])),
            new OA\Parameter(name: "locationFrom", in: "query", schema: new OA\Schema(type: "string")),
            new OA\Parameter(name: "locationTo", in: "query", schema: new OA\Schema(type: "string")),
        ],
        responses: [
            new OA\Response(response: 200, description: "Activity list", content: new OA\JsonContent())
        ]
    )]
    public function list(ActivityListRequest $request)
    {
        return Activity::filterByRequest($request)->orderBy('timeFrom')->paginate(50);
    }

    #[OA\Post(
        path: "/api/activities/upload-roster",
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "roster", type: "string", format: "binary"),
                        new OA\Property(property: "type", type: "string", enum: ["html"]),
                    ],
                    type: "object"
                )
            )
        ),
        tags: ["Activities"],
        parameters: [

        ],
        responses: [
            new OA\Response(response: 200, description: "Success", content: new OA\JsonContent())
        ]
    )]
    public function uploadRoster(UploadRosterRequest $request, RosterImporter $importer)
    {
        $importer->import($request->getRoster()->getPathname(), $request->getType());

        return ['status' => 'success'];
    }
}
