<?php


namespace Tests\Setup;


use App\Http\Clients\OpencastClient;
use App\Models\Series;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use PHPUnit\Util\Xml;


trait WorksWithOpencastClient {

    use WithFaker;

    public function swapOpencastClient(): MockHandler
    {
        $mockHandler = new MockHandler();

        $client = new OpencastClient([
            'handler' => HandlerStack::create($mockHandler)
        ]);

        $this->app->instance(OpencastClient::class, $client);

        return $mockHandler;
    }

    public function mockHealthResponse(): Response
    {
        return new Response(200, [], json_encode([
            "releaseId"   => "8.10.0",
            "description" => "Opencast node's health status",
            "serviceId"   => "http://localhost:8080",
            "version"     => "1",
            "status"      => "pass",
        ]));
    }

    public function mockCreateSeriesResponse(): Response
    {
        return new Response(201, [
            'Location' => [
                '0' => 'http://localhost:8080/api/series/' . Str::uuid()
            ]
        ]);
    }

    public function mockIngestMediaPackageResponse(): Response
    {
        return new Response(200, [], json_encode([
            new Xml()
        ]));
    }

    public function mockSeriesRunningWorkflowsResponse(Series $series, bool $multiple): Response
    {
        $workflows = ($multiple) ? [
            [
                'id'           => 2006754,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'id'     => Str::uuid(),
                    'title'  => $this->faker->sentence,
                    'series' => $series->opencast_series_id,
                ],
                'operations'   => [
                    'operation' =>
                        [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                        [
                            'id'    => 'encode',
                            'state' => 'RUNNING'
                        ]
                ]
            ],
            [
                'id'           => 2006752,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'id'     => Str::uuid(),
                    'title'  => $this->faker->sentence,
                    'series' => $series->opencast_series_id ,
                ],
                'operations'   => [
                    'operation' =>
                        [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                    [
                        'id'    => 'encode',
                        'state' => 'RUNNING'
                    ]
                ]
            ],
        ] :
            [
                'id'           => 2006754,
                'state'        => 'RUNNING',
                'title'        => 'Transcode after upload',
                'mediapackage' => [
                    'id'     => Str::uuid(),
                    'title'  => $this->faker->sentence,
                    'series' => $series->opencast_series_id,
                ],
                'operations'   => [
                    'operation' =>
                        [
                            'id'    => 'ingest-download',
                            'state' => 'SUCCEEDED'
                        ],
                    [
                        'id'    => 'encode',
                        'state' => 'RUNNING'
                    ]
                ]
        ];

        return new Response(201, [], json_encode([
            'workflows' => [
                'startPage'  => 0,
                'count'      => 20,
                'searchTime' => 2,
                'totalCount' => $multiple ? 2:1,
                'workflow'   => $workflows
            ]
        ]));
    }
}

