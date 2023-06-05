<?php

declare(strict_types=1);

namespace WayOfDev\Paginator;

use Illuminate\Pagination\Paginator as IlluminatePaginator;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\HttpHeaderSerializer;
use WayOfDev\Paginator\Concerns\WithHeaders;

final class Paginator extends IlluminatePaginator
{
    use WithHeaders;

    public function toArray(): array
    {
        return $this->items->toArray();
    }

    public function headers(): array
    {
        $linkProvider = $this->headerLinks(new GenericLinkProvider());

        return [
            'Link' => (new HttpHeaderSerializer())->serialize($linkProvider->getLinks()),
            'X-Page' => $this->currentPage(),
            'X-Per-Page' => $this->perPage(),
            'X-Total-Count' => $this->lastItem(),
        ];
    }

    protected function pages(): array
    {
        $pages = [];
        $pages['current'] = $this->currentPage();

        if ($this->hasMorePages()) {
            $pages['next'] = $this->currentPage() + 1;
        }

        if ($this->currentPage() > 1) {
            $pages['prev'] = $this->currentPage() - 1;
        }

        return $pages;
    }
}
