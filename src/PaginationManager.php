<?php

declare(strict_types=1);

namespace WayOfDev\Paginator;

interface PaginationManager
{
    public function paginate(int $perPage = 20, int $page = 1, string $pageName = 'page'): CyclePaginator;
}
