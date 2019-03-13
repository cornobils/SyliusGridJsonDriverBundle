<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;


final class QueryBuilder
{
    /**
     * @var string
     */
    private $url;

    public function __construct(string $host, string $url)
    {
        $this->url = $host . $url . "?";
    }

    public function __toString() {
        return $this->url;
    }

    public function addFilter(string $filter): void
    {
        $this->url = $this->url . $filter;
    }
}
