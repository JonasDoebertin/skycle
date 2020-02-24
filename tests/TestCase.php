<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        RefreshDatabase;

    protected function jsonStub(string $path, bool $assoc = false)
    {
        return json_decode($this->stub($path), $assoc);
    }

    protected function stub(string $path)
    {
        return File::get(base_path("tests/stubs/{$path}"));
    }
}
