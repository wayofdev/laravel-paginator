<?php

declare(strict_types=1);

namespace WayOfDev\Paginator\Tests;

use Spiral\Pagination\Paginator as SpiralPaginator;
use TiagoHillebrandt\ParseLinkHeader;
use WayOfDev\Paginator\CyclePaginator;

use function array_slice;
use function count;

final class CyclePaginatorTest extends TestCase
{
    private int $page = 2;

    private int $perPage = 5;

    /**
     * @test
     */
    public function it_gets_total_items(): void
    {
        self::assertEquals(34, $this->getPaginator()->total());
    }

    /**
     * @test
     */
    public function it_gets_last_page(): void
    {
        self::assertEquals(7, $this->getPaginator()->lastPage());
    }

    /**
     * @test
     */
    public function it_gets_next_page_url_when_it_exists(): void
    {
        self::assertEquals('/?page=3', $this->getPaginator()->nextPageUrl());
    }

    /**
     * @test
     */
    public function it_gets_next_page_url_when_it_not_exists(): void
    {
        self::assertNull(
            $this->getPaginator(1, 200)->nextPageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_previous_page_url_when_it_exists(): void
    {
        self::assertEquals('/?page=1', $this->getPaginator()->previousPageUrl());
    }

    /**
     * @test
     */
    public function it_gets_previous_page_url_when_it_not_exists(): void
    {
        self::assertNull(
            $this->getPaginator(1, 1)->previousPageUrl()
        );
    }

    /**
     * @test
     */
    public function it_gets_items(): void
    {
        $items = array_slice($this->items, 5, 5, true);

        self::assertEquals($items, $this->getPaginator()->items());
    }

    /**
     * @test
     */
    public function it_gets_first_item(): void
    {
        self::assertEquals(6, $this->getPaginator()->firstItem());
    }

    /**
     * @test
     */
    public function it_gets_last_item(): void
    {
        self::assertEquals(10, $this->getPaginator()->lastItem());
    }

    /**
     * @test
     */
    public function it_gets_per_page(): void
    {
        self::assertEquals(5, $this->getPaginator()->perPage());
    }

    /**
     * @test
     */
    public function it_gets_current_page(): void
    {
        self::assertEquals(2, $this->getPaginator()->currentPage());
    }

    /**
     * @test
     */
    public function it_gets_page_name(): void
    {
        self::assertEquals('page', $this->getPaginator()->getPageName());
    }

    /**
     * @test
     */
    public function it_gets_count(): void
    {
        self::assertEquals(5, $this->getPaginator()->count());
    }

    /**
     * @test
     */
    public function it_returns_links(): void
    {
        $paginator = $this->getPaginator();
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

    /**
     * @test
     */
    public function it_returns_response_headers(): void
    {
        $paginator = $this->getPaginator();
        $headers = $paginator->headers();

        self::assertEquals(2, $headers['X-Page']);
        self::assertEquals(5, $headers['X-Per-Page']);
        self::assertEquals(34, $headers['X-Total-Count']);
        self::assertEquals(5, $headers['X-Count']);
    }

    protected function getPaginator(int $perPage = 5, int $currentPage = 2): CyclePaginator
    {
        $collection = collect($this->items)->forPage($this->page, $this->perPage);

        return new CyclePaginator(
            (new SpiralPaginator($perPage))
                ->withPage($currentPage)
                ->withCount(count($this->items)),
            $collection,
            'page'
        );
    }
}
