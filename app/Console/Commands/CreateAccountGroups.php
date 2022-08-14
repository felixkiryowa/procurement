<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ledger;

class CreateAccountGroups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:accountgroup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an account group';

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

        $groups = ['assets', 'liabilities', 'expenses', 'revenue and funding', 'equity'];
        foreach ($groups as  $value) {
            if($value == 'assets') {
                $account_group = Ledger::create( [
                    'name' => strtoupper( $value ),
                    // 'item_code' => '1000-5000'
                ] );
                if($account_group) {
                    $this->info('Successfully created '.$value.' account group');
                }
            }else if($value == 'liabilities') {
                $account_group = Ledger::create( [
                    'name' => strtoupper( $value ),
                    // 'item_code' => '6000-10000'
                ] );
                if($account_group) {
                    $this->info('Successfully created '.$value.' account group');
                }
            }else if($value == 'expenses') {
                $account_group = Ledger::create( [
                    'name' => strtoupper( $value ),
                    // 'item_code' => '11000-16000'
                ] );
                if($account_group) {
                    $this->info('Successfully created '.$value.' account group');
                }
            }else if($value == 'income') {
                $account_group = Ledger::create( [
                    'name' => strtoupper( $value ),
                    // 'item_code' => '17000-22000'
                ] );
                if($account_group) {
                    $this->info('Successfully created '.$value.' account group');
                }

            }else {
                $account_group = Ledger::create( [
                    'name' => strtoupper( $value ),
                    // 'item_code' => '23000-27000'
                ] );
                if($account_group) {
                    $this->info('Successfully created '.$value.' account group');
                }
            }
        }
       
    }
}
