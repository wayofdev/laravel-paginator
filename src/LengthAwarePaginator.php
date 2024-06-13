<?php

declare(strict_types=1);

namespace WayOfDev\Paginator;

use Illuminate\Pagination\LengthAwarePaginator as IlluminatePaginator;
use Symfony\Component\WebLink\GenericLinkProvider;
use Symfony\Component\WebLink\HttpHeaderSerializer;
use WayOfDev\Paginator\Concerns\WithHeaders;

use function array_values;

/**
 * Extends the default Laravel LengthAwarePaginator to add headers and a toArray method.
 * Can be used with Eloquent models and collections.
 */
final class LengthAwarePaginator extends IlluminatePaginator
{
    use WithHeaders;

    public function __construct(IlluminatePaginator $paginator, array $options = [])
    {
        parent::__construct(
            $paginator->items(),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            $options
        );

        $this->query = $paginator->query;
        $this->path = $paginator->path;
        $this->fragment = $paginator->fragment;
        $this->pageName = $paginator->pageName;
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
}
