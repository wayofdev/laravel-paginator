<?php

declare(strict_types=1);

namespace WayOfDev\Paginator\Concerns;

use Illuminate\Http\JsonResponse;
use Psr\Link\EvolvableLinkProviderInterface;
use Symfony\Component\WebLink\Link;

trait WithHeaders
{
    public function headerLinks(EvolvableLinkProviderInterface $linkProvider): EvolvableLinkProviderInterface
    {
        foreach ($this->pages() as $rel => $page) {
            $href = $this->url($page);
            $linkProvider = $linkProvider->withLink(new Link($rel, $href));
        }

        return $linkProvider;
    }

    public function toResponse(): JsonResponse
    {
        return (new JsonResponse($this->toArray()))
            ->withHeaders($this->headers());
    }

    private function currentCount(): int
    {
        return $this->items->count();
    }

    private function pages(): array
    {
        $currentPage = $this->currentPage();
        $lastPage = $this->lastPage();
        $pages = ['first' => 1, 'last' => $lastPage];

        if ($currentPage > 1) {
            $pages['prev'] = $currentPage - 1;
        }
        if ($currentPage < $lastPage) {
            $pages['next'] = $currentPage + 1;
        }

        return $pages;
    }
}
