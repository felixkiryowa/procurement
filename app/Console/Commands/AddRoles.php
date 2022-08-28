<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Role;

class AddRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:roles';

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
        $roles = [
            'Provider', 'Company', 'Procurement Officer', 'Super Systems Administrator'
        ];

        foreach ($roles as $key => $value) {
            Role::create([
                'name' => $value
            ]);
        }


        echo "Roles Added Successfully";
    }
}
