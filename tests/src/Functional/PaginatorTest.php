<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use TiagoHillebrandt\ParseLinkHeader;
use WayOfDev\Paginator\Paginator;

final class PaginatorTest extends TestCase
{
    private int $perPage = 5;

    #[Test]
    public function it_sets_headers(): void
    {
        $paginator = new Paginator($this->items, 5);

        $expectedHeaders = [
            'Link' => '</?page=1>; rel="current",</?page=2>; rel="next"',
            'X-Page' => 1,
            'X-Per-Page' => 5,
            'X-Total-Count' => 5,
        ];

        $this::assertEquals($expectedHeaders, $paginator->headers());
    }

    #[Test]
    public function it_returns_links(): void
    {
        $paginator = new Paginator($this->items, $this->perPage);
        $headers = $paginator->headers();

        $links = (new ParseLinkHeader($headers['Link']))->toArray();

        self::assertEquals('/?page=1', $links['current']['link']);
        self::assertEquals('1', $links['current']['page']);

        self::assertEquals('/?page=2', $links['next']['link']);
        self::assertEquals('2', $links['next']['page']);
    }
}
