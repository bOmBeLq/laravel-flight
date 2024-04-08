<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Activity;
use App\Models\Enum\ActivityType;
use App\Service\RosterImport\RosterImporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Spatie\Snapshots\MatchesSnapshots;
use Tests\TestCase;

class ActivityControllerTest extends TestCase
{
    use MatchesSnapshots;
    use RefreshDatabase;

    public const TEST_ROSTER_FILE = __DIR__ . '/_resources/roster.html';

    public function testList()
    {
        $this->travelTo(new Carbon('2022-01-14 08:00'));

        /** @var $rosterImporter RosterImporter */
        $rosterImporter = $this->app->get(RosterImporter::class);
        $rosterImporter->import(self::TEST_ROSTER_FILE, 'html');


        $testRequest = function (array $query) {
            $response = $this->withHeaders(['Accept' => 'application/json'])->call('GET', '/api/activities', $query);
            $response->assertStatus(200);
            $this->assertMatchesJsonSnapshot($response->json());
        };

        $testRequest([]);
        $testRequest(['dateTimeFrom' => '2022-01-11 15:00:00', 'dateTimeTo' => '2022-01-13 12:00:00']);
        $testRequest(['period' => 'nextWeek']);
        $testRequest(['period' => 'nextWeek', 'type' => ActivityType::FLIGHT->value]);
        $testRequest(['period' => 'nextWeek', 'type' => ActivityType::STAND_BY->value]);
        $testRequest(['locationFrom' => 'KRP', 'locationTo' => 'CPH']);
    }

    public function testListInvalid()
    {
        $response = $this->json('GET', '/api/activities', ['dateTimeFrom' => 'invalid']);
        $response->assertStatus(422);
        $this->assertEquals([
            "message" => "The date time from field must match the format Y-m-d H:i:s.",
            "errors" => [
                "dateTimeFrom" => [
                    "The date time from field must match the format Y-m-d H:i:s."
                ],
            ]
        ], $response->json());
    }

    public function testUploadRoster(): void
    {
        $this->travelTo(new Carbon('2022-01-14 08:00'));
        Storage::fake('uploads');

        $response = $this->postJson('/api/activities/upload-roster', [
            'roster' => UploadedFile::fake()->createWithContent('roster.html', file_get_contents(__DIR__ . '/_resources/roster.html')),
            'type' => 'html'
        ]);
        $response->assertStatus(200);

        $activities = Activity::all()->collect();

        $this->assertMatchesJsonSnapshot($activities->toArray());
    }
    public function testUploadRosterCrossMonth(): void
    {
        $this->travelTo(new Carbon('2022-01-14 08:00'));
        Storage::fake('uploads');

        $response = $this->postJson('/api/activities/upload-roster', [
            'roster' => UploadedFile::fake()->createWithContent('cross_month_roster.html', file_get_contents(__DIR__ . '/_resources/cross_month_roster.html')),
            'type' => 'html'
        ]);
        $response->assertStatus(200);

        $activities = Activity::all()->collect();

        $this->assertMatchesJsonSnapshot($activities->toArray());
    }

    public function testUploadRosterMissingValues()
    {
        $response = $this->postJson('/api/activities/upload-roster');
        $response->assertStatus(422);
        $this->assertEquals([
            "message" => "The roster field is required. (and 1 more error)",
            "errors" => [
                "roster" => [
                    "The roster field is required."
                ],
                "type" => [
                    "The type field is required."
                ]
            ]
        ], $response->json());
    }

    public function testUploadRosterInvalidValues()
    {
        $response = $this->postJson('/api/activities/upload-roster', ['type' => 'x']);
        $response->assertStatus(422);
        $this->assertEquals([
            "message" => "The roster field is required. (and 1 more error)",
            "errors" => [
                "roster" => [
                    "The roster field is required."
                ],
                "type" => [
                    "Roster type x is not supported"
                ]
            ]
        ], $response->json());
    }
}
