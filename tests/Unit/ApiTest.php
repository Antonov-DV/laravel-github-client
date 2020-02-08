<?php

namespace Tests\Unit;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    /** @var Client $http */
    protected $http;

    /**
     *
     */
    public function setUp(): void
    {
        $this->http = new Client();
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->http->request('GET', 'http://localhost/api/rest');

        $this->assertEquals(200, $response->getStatusCode());

        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);
    }
/*
    public function testImportDelete()
    {
        $githubService = new GithubService([]);
        $repos         = $githubService->listMyRepos();

        $githubService->importRepo(Arr::get($repos, '0.owner.login'), Arr::get($repos, '0.name'));
        $githubService->deleteRepoCommits(Arr::get($repos, '0.owner.login'), Arr::get($repos, '0.name'));

        $this->assertFalse(Commit::query()->where([
            'repo_id' => Arr::get($repos, '0.id'),
        ])->exists());
    }*/

}
