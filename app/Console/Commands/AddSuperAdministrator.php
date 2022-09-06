<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AddSuperAdministrator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:superadmin';

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
     * @return int
     */
    public function handle()
    {
        User::create([
            'userName' => 'daddyfelix56@gmail.com',
            'password' => Hash::make('password@123'),
            'email' => 'daddyfelix56@gmail.com',
            'firstName' => 'Angel',
            'lastName' => 'Nabakoza',
            'account_type_id'=> 4,
            'user_role' => 'Super Systems Administrator'
          ]);
    }
}
