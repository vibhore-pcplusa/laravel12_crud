<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SilencedJob implements ShouldQueue
{
    public function handle()
    {
        try {
            // job logic
            \Log::info("silenced from try");
            throw new \Exception('Something went wrong');
        } catch (\Throwable $e) {
            // 👇 THIS is what makes it "silenced" in Horizon
            \Log::info("silenced from catch");
            $this->fail($e, false);
        }
    }
}
