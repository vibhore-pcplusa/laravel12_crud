<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DbCheckTest extends TestCase
{
    public function not_test_database_used()
    {
        //dump(config('database.default'), config('database.connections'));
        $this->assertTrue(true);
    }
}
