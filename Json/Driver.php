<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;

use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\DriverInterface;
use Sylius\Component\Grid\Parameters;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

final class Driver implements DriverInterface
{
    public const NAME = 'json';

    private $curlAdapter;

    private $router;

    public function __construct(CurlAdapterInterface $curlAdapter, RouterInterface $router)
    {
        $this->curlAdapter = $curlAdapter;
        $this->router = $router;
    }


    public function getDataSource(array $configuration, Parameters $parameters): DataSourceInterface
    {
        if (!array_key_exists('route', $configuration)) {
            if (!array_key_exists('url', $configuration)) {
                throw new \InvalidArgumentException('"url" or "route" must be configured.');
            }
            if (!array_key_exists('host', $configuration)) {
                throw new \InvalidArgumentException('"host" must be configured.');
            }
        } else {
            $arguments = isset($configuration['arguments']) ?$configuration['arguments'] : [];
            $arr = [];

            foreach ($arguments as $argument)
            {
                $arr = array_merge($arr, $argument);
            }

            $configuration['route'] = $this->router->generate($configuration['route'], $arr, UrlGeneratorInterface::ABSOLUTE_URL);
        }
        return new DataSource($configuration, $this->curlAdapter);
    }
}
