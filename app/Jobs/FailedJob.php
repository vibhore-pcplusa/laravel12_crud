<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FailedJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        \Log::info("FailedJob before exception");
        //throw new \Exception('Something went wrong');
        throw new Exception("This job is failed");
    \Log::info("FailedJob executed successfully!");
    }
}
