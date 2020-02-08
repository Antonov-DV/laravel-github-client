<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteRepoCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deleteRepoCommits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $repoLogin = $this->ask('repo Login:');
        $repoName  = $this->ask('repo Name:');

        try {
            echo app('github')->deleteRepoCommits($repoLogin, $repoName);
        } catch (\Exception $exc) {
            $this->error($exc->getMessage());
        }

        return;
    }
}
