<?php
/**
 * Created by PhpStorm.
 * User: dmitry
 * Date: 02.02.20
 * Time: 23:21
 */

namespace App;


use Github\Api\Repository\Commits;
use Github\Client;
use Illuminate\Support\Arr;

/**
 * Class GithubService
 * @package App
 */
class GithubService
{
    /** @var Client $client */
    protected $client;

    /** @var \Github\Api\Repo $repoClient */
    protected $repoClient;

    /** @var Commits $commitsClient */
    protected $commitsClient;

    /** @var \Github\Api\User $userClient */
    protected $userClient;

    /**
     * GithubService constructor.
     *
     * @param $params
     */
    public function __construct($params)
    {
        $this->client        = new \Github\Client();
        $this->userClient    = $this->client->api('user');
        $this->repoClient    = $this->client->api('repo');
        $this->commitsClient = $this->repoClient->commits();

        $this->client->authenticate(
            env('GITHUB_TOKEN_OR_LOGIN'),
            env('GITHUB_PASSWORD'),
            env('GITHUB_AUTH_METHOD'));
    }

    /**
     * @return array
     */
    public function listMyRepos()
    {
        return $this->client->user()->myRepositories();
    }

    /**
     * @param $userName
     *
     * @return array
     */
    public function listUserRepos($userName)
    {
        return $this->userClient->repositories($userName);
    }

    /**
     * @param $repoName
     * @param $repoDesc
     * @param $repoLink
     *
     * @return array
     */
    public function createRepo($repoName, $repoDesc, $repoLink)
    {
        return $this->repoClient->create($repoName, $repoDesc, $repoLink, true);
    }

    /**
     * @param     $login
     * @param     $repoName
     * @param int $page
     *
     * @return array|string
     */
    public function listCommits($login, $repoName, $page = 1)
    {
        return $this->commitsClient
            ->setPerPage(20)
            ->setPage($page)
            ->all($login, $repoName, ['sha' => 'master']);
    }

    /**
     * @return array
     */
    public function listCommitsWithRepos()
    {
        $repos = $this->listMyRepos();

        foreach ($repos as &$repo) {

            try {
                $repo['commits'] = $this->listCommits($repo['owner']['login'], $repo['name']);
            } catch (\Exception $exc) {
                //log
            }

        }

        return $repos;
    }

    /**
     * @param $login
     * @param $repoName
     *
     * @return string
     */
    public function importRepo($login, $repoName)
    {
        $repoData = $this->repoClient->show($login, $repoName);

        $repoModel = new Repo();

        try {
            $repoModel->fill([
                'id'          => Arr::get($repoData, 'id'),
                'name'        => Arr::get($repoData, 'name'),
                'owner_login' => Arr::get($repoData, 'owner.login'),
                'owner_id'    => Arr::get($repoData, 'owner.id'),
                'data'        => json_encode($repoData),
            ]);

            if ($repoModel->isValid()) {
                $repoModel->save();
            }

        } catch (\Exception $exc) {
            //log
        }

        $repoCommits = $this->listCommits($login, $repoName);
        $page        = 1;

        while (count($repoCommits) > 0) {
            foreach ($repoCommits as $repoCommit) {

                try {
                    $modelData   = [
                        'repo_id'     => Arr::get($repoData, 'id'),
                        'message'     => Arr::get($repoCommit, 'commit.message'),
                        'author_name' => Arr::get($repoCommit, 'commit.author.name'),
                        'sha'         => Arr::get($repoCommit, 'sha'),
                        'data'        => json_encode($repoCommit),
                    ];
                    $commitModel = new Commit();
                    $commitModel->fill($modelData);

                    if ($commitModel->isValid()) {
                        $commitModel->save();
                    }

                } catch (\Exception $exc) {
                    //log
                }
            }
            $page++;
            $repoCommits = $this->listCommits($login, $repoName, $page);
        }

        return 'ok';
    }

    /**
     * @param $login
     * @param $repoName
     *
     * @return string
     */
    public function deleteRepoCommits($login, $repoName)
    {
        $repoModel = Repo::query()->where([
            'owner_login' => $login,
            'name'        => $repoName,
        ])->get()->first();

        if (!empty($repoModel)) {
            Commit::where('repo_id', $repoModel->getAttribute('id'))->delete();
            Repo::where('id', $repoModel->getAttribute('id'))->delete();

            return 'ok';
        }

        return 'Repo not found';
    }
}