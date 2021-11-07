<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

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
        if (User::whereEmail($this->argument('email'))->exists()) {
            $this->error(
                'A user with this email already exists. If you want to reset the password, please use the reset command.'
            );
            return 1;
        }

        $password = \Str::random(16);
        User::create([
            'email' => $this->argument('email'),
            'password' => Hash::make($password),
        ]);
        $this->info(
            'User created! You can now login using ' . $password . ' as your password. Please change it asap!'
        );
        return 0;
    }
}
