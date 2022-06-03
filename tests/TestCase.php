<?php

namespace PacoOrozco\OpenSSH\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function getStub(string $nameOfStub): string
    {
        return __DIR__ . "/stubs/{$nameOfStub}";
    }

    public function getTempPath(string $fileName): string
    {
        return __DIR__ . "/temp/{$fileName}";
    }
}
