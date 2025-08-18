<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class CleanExpiredPasswordResetTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password-reset:clean-expired-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove expired password reset tokens from the database.';

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
        DB::table('password_resets')->where('expires_at', '<', now())->delete();

        $this->info('Expired password reset tokens have been removed from the database.');
    }
}
