<?php

declare(strict_types=1);

namespace WayOfDev\Tests\Functional;

use Illuminate\Pagination\LengthAwarePaginator as IlluminatePaginator;
use PHPUnit\Framework\Attributes\Test;
use TiagoHillebrandt\ParseLinkHeader;
use WayOfDev\Paginator\LengthAwarePaginator;

use function count;

final class LengthAwarePaginatorTest extends TestCase
{
    private int $page = 2;

    private int $perPage = 5;

    private IlluminatePaginator $queryBuilderPaginator;

    public function setUp(): void
    {
        parent::setUp();

        $collection = collect($this->items)->forPage($this->page, $this->perPage);
        $this->queryBuilderPaginator = new IlluminatePaginator(
            $collection,
            count($this->items),
            $this->perPage,
            $this->page
        );
    }

    #[Test]
    public function it_returns_links(): void
    {
        $paginator = new LengthAwarePaginator($this->queryBuilderPaginator);
        $headers = $paginator->headers();

        $links = (new ParseLinkHeader($headers['Link']))->toArray();

        self::assertEquals('/?page=1', $links['first']['link']);
        self::assertEquals('1', $links['first']['page']);

        self::assertEquals('/?page=7', $links['last']['link']);
        self::assertEquals('7', $links['last']['page']);

        self::assertEquals('/?page=1', $links['prev']['link']);
        self::assertEquals('1', $links['prev']['page']);

        self::assertEquals('/?page=3', $links['next']['link']);
        self::assertEquals('3', $links['next']['page']);
    }

    #[Test]
    public function it_returns_response_headers(): void
    {
        $paginator = new LengthAwarePaginator($this->queryBuilderPaginator);
        $headers = $paginator->headers();

        self::assertEquals(2, $headers['X-Page']);
        self::assertEquals(5, $headers['X-Per-Page']);
        self::assertEquals(34, $headers['X-Total-Count']);
        self::assertEquals(5, $headers['X-Count']);
    }
}
