<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a users password';

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
        $user = User::whereEmail($this->argument('email'))->first();

        if (!$user) {
            $this->info('User not found!');
            return 1;
        }

        $password = \Str::random(16);
        $user->password = Hash::make($password);
        $user->save();

        $this->info('The password was reset successfully! You can now login using ' . $password);

        return 0;
    }
}
