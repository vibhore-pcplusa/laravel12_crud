<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TestJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private int $a ,private int $b)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        \Log::info("Sum of two numbers is " . ($this->a + $this->b));
        \Log::info("TestJob executed successfully!");
    }
}
