<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Parameters;

final class Driver implements DriverInterface
{
    public const NAME = 'json';

    private $curlAdapter;

    public function __construct(CurlAdapterInterface $curlAdapter)
    {
        $this->curlAdapter = $curlAdapter;
    }


    public function getDataSource(array $configuration, Parameters $parameters): DataSourceInterface
    {
        if (!array_key_exists('url', $configuration)) {
            throw new \InvalidArgumentException('"url" must be configured.');
        }
        if (!array_key_exists('host', $configuration)) {
            throw new \InvalidArgumentException('"host" must be configured.');
        }
        return new DataSource($configuration, $this->curlAdapter);
    }


}