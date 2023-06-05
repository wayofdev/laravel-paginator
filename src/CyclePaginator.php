<?php

declare(strict_types=1);

namespace WayOfDev\Paginator;

use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Spiral\Pagination\Paginator as SpiralPaginator;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\HttpHeaderSerializer;
use WayOfDev\Paginator\Concerns\NotRenders;
use WayOfDev\Paginator\Concerns\WithHeaders;

use function array_values;

final class CyclePaginator extends AbstractPaginator implements LengthAwarePaginatorContract
{
    use WithHeaders;
    use NotRenders;

    protected LazyCollection $results;

    protected SpiralPaginator $paginator;

    public function __construct(SpiralPaginator $paginator, Collection $items, string $pageName = 'page')
    {
        $this->paginator = $paginator;
        $this->perPage = $paginator->getLimit();
        $this->currentPage = $paginator->getPage();
        $this->pageName = $pageName;
        $this->items = $items;
    }

    public function total(): int
    {
        return $this->paginator->count();
    }

    public function lastPage(): int
    {
        return $this->paginator->countPages();
    }

    public function nextPageUrl(): ?string
    {
        if ($this->hasMorePages()) {
            return $this->url($this->currentPage() + 1);
        }

        return null;
    }

    public function hasMorePages(): bool
    {
        return $this->currentPage() < $this->lastPage();
    }

    public function toArray(): array
    {
        return array_values($this->items->toArray());
    }

    public function headers(): array
    {
        $linkProvider = $this->headerLinks(new GenericLinkProvider());

        return [
            'Link' => (new HttpHeaderSerializer())->serialize($linkProvider->getLinks()),
            'X-Page' => $this->currentPage(),
            'X-Per-Page' => $this->perPage(),
            'X-Total-Count' => $this->total(),
            'X-Count' => $this->currentCount(),
        ];
    }

    private function currentCount(): int
    {
        return $this->items->count();
    }
}
