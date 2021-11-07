<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a list of all users';

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
     * @return int
     */
    public function handle()
    {
        $this->table(
            ['Id', 'Email'],
            User::all(['id', 'email'])->toArray()
        );
        return 0;
    }
}
