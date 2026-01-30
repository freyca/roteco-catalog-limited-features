<?php

declare(strict_types=1);

namespace App\Factories\BreadCrumbs;

interface BreadCrumbsFactory
{
    /**
     * @return array<string, string>
     */
    public function getBreadCrumbs(): array;
}
