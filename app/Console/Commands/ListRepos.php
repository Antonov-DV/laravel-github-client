<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ListRepos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listRepos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns repos listing';

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
        try {
            $repos = app('github')->listMyRepos();

            if (!empty($repos)) {

                $rows = [];

                foreach ($repos as $repo) {
                    $rows [] = [
                        Arr::get($repo, 'id'),
                        Arr::get($repo, 'name'),
                        Arr::get($repo, 'owner.login'),
                    ];
                }

                $this->table(['id', 'name', 'owner'], $rows);
            } else {
                echo 'No repos';
            }
        } catch (\Exception $exc) {
            $this->error($exc->getMessage());
        }

        return;
    }
}
