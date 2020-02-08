<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CreateRepo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'createRepo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new repo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $repoName = $this->ask('Repo Name:');
        $repoDesc = $this->ask('repo Description:', '');
        $repoLink = $this->ask('repo link:', '');

        if (!empty($repoName)) {

            try {
                $result = app('github')->createRepo($repoName, $repoDesc, $repoLink);

                echo 'Repo created!';
                $this->table(['id', 'name', 'owner'], [
                    Arr::get($result, 'id'),
                    Arr::get($result, 'name'),
                    Arr::get($result, 'owner.login')
                ]);
            } catch (\Exception $exc) {
                $this->error($exc->getMessage());
            }

            return ;
        }

        throw new BadRequestHttpException('The repoName parameter should be set');
    }
}
