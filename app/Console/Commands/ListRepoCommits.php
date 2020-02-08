<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ListRepoCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listRepoCommits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns repo commits listing';

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
            $commits = app('github')->listCommits(
                $this->ask('user login:'),
                $this->ask('repo name:'),
                $this->ask('page:', '0'));

            if (!empty($commits)) {

                $rows = [];

                foreach ($commits as $commit) {
                    $rows []= [
                        Arr::get($commit, 'sha'),
                        Arr::get($commit, 'commit.message'),
                        Arr::get($commit, 'commit.author.name')
                    ];
                }

                $this->table(['sha', 'message', 'author'], $rows);

            } else {
                echo 'No commits found';
            }
        } catch (\Exception $exc) {
            $this->error($exc->getMessage());
        }

        return ;
    }
}
