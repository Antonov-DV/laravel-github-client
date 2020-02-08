<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class RestController
 * @package App\Http\Controllers
 */
class RestController extends Controller
{

    /**
     * RestController constructor.
     */
    public function __construct()
    {
        header('Access-Control-Allow-Origin: *');
    }

    /**
     * @return string
     */
    public function index()
    {
        try {
            $repos = app('github')->listMyRepos();

            return response()->json($repos);
        } catch (\Exception $exc) {
            return response($exc->getMessage(), 500);
        }
    }

    /**
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function options()
    {

        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        return response('');
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function listRepoCommits(Request $request)
    {
        try {
            $commits = app('github')->listCommits(
                $request->get('login'),
                $request->get('repoName'),
                $request->get('page', 0));

            return response()->json($commits);
        } catch (\Exception $exc) {
            return response($exc->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function createRepo(Request $request)
    {
        try {
            $requestData = $request->json()->all();

            $repoName = Arr::get($requestData, 'repoName');
            $repoDesc = Arr::get($requestData, 'repoDesc');
            $repoLink = Arr::get($requestData, 'repoLink');

            if (!empty($repoName)) {
                return response()->json(app('github')->createRepo($repoName, $repoDesc, $repoLink));
            }

            throw new BadRequestHttpException('The repoName parameter should be set');

        } catch (\Exception $exc) {
            return response($exc->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function importRepo(Request $request)
    {
        try {
            $requestData = $request->json()->all();

            $repoName  = Arr::get($requestData, 'repoName');
            $repoLogin = Arr::get($requestData, 'repoLogin');

            return response()->json(app('github')->importRepo($repoLogin, $repoName));
        } catch (\Exception $exc) {
            return response($exc->getMessage(), 500);
        }
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function deleteRepoCommits(Request $request)
    {
        try {
            $requestData = $request->json()->all();

            $repoName  = Arr::get($requestData, 'repoName');
            $repoLogin = Arr::get($requestData, 'repoLogin');

            return app('github')->deleteRepoCommits($repoLogin, $repoName);
        } catch (\Exception $exc) {
            return response($exc->getMessage(), 500);
        }
    }
}
