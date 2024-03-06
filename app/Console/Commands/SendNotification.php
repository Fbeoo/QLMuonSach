<?php

namespace App\Console\Commands;

use App\Repositories\UserRepositoryInterface;
use Illuminate\Console\Command;
use App\Jobs\SendNotification as JobSendNotification;
use Illuminate\Support\Facades\Log;

class SendNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendNotification:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $userRepository;

    /**
     * @param $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = $this->userRepository->getAllRequestRentBookExpirationOfUser();
        if ($users->isNotEmpty()) {
            foreach ($users as $user) {
                JobSendNotification::dispatch($user->toArray());
            }
        }
    }
}
