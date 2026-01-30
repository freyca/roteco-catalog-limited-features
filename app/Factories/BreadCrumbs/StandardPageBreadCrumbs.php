<?php

declare(strict_types=1);

namespace App\Factories\BreadCrumbs;

class StandardPageBreadCrumbs implements BreadCrumbsFactory
{
    /**
     * @var array<string, string>
     */
    protected array $default_bread_crumb = [];

    /**
     * @var array<string, string>
     */
    protected array $bread_crumbs = [];

    /**
     * @param  array<string, string>  $bread_crumbs
     */
    public function __construct(array $bread_crumbs)
    {
        $this->setDefaultBreadCrumb();

        $this->bread_crumbs = array_merge($this->default_bread_crumb, $bread_crumbs);
    }

    /**
     * @return array<string, string>
     */
    public function getBreadCrumbs(): array
    {
        return $this->bread_crumbs;
    }

    protected function setDefaultBreadCrumb(): void
    {
        $this->default_bread_crumb = ['heroicon-m-home' => route('home')];
    }
}
