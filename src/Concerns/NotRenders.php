<?php

declare(strict_types=1);

namespace WayOfDev\Paginator\Concerns;

trait NotRenders
{
    public function render($view = null, $data = []): string
    {
        return '';
    }
}
