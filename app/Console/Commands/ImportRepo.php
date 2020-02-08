<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportRepo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'importRepo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports Repo into DB';

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
        $repoName = $this->ask('repo Name:');
        $repoLogin = $this->ask('user Login');

        try {
            echo app('github')->importRepo($repoLogin, $repoName);
        } catch (\Exception $exc) {
            $this->error($exc->getMessage());
        }

        return ;
    }
}
