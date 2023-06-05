<?php

declare(strict_types=1);

namespace WayOfDev\Paginator\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WayOfDev\Paginator\Bridge\Laravel\Providers\PaginatorServiceProvider;

abstract class TestCase extends Orchestra
{
    protected array $items = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->items = require __DIR__ . '/../app/items.php';
    }

    protected function getPackageProviders($app): array
    {
        return [
            PaginatorServiceProvider::class,
        ];
    }
}
