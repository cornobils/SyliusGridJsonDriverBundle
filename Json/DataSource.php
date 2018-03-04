<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;

use Pagerfanta\Pagerfanta;
use Sylius\Component\Grid\Data\DataSourceInterface;
use Sylius\Component\Grid\Data\ExpressionBuilderInterface;
use Sylius\Component\Grid\Parameters;

final class DataSource implements DataSourceInterface
{
    /**
     * @var ExpressionBuilderInterface
     */
    private $expressionBuilder;

    /** @var QueryBuilder */
    private $queryBuilder;

    /** @var  */
    private $curlAdapter;

    public function __construct(array $configuration, CurlAdapterInterface $curlAdapter)
    {
        if ($configuration['route'] !== null) {
            $this->queryBuilder = new QueryBuilder($configuration['route'], "");
        } else {
            $this->queryBuilder = new QueryBuilder($configuration['host'], $configuration['url']);
        }
        $this->expressionBuilder = new ExpressionBuilder($this->queryBuilder);
        $this->curlAdapter = $curlAdapter;
    }

    public function restrict($expression, string $condition = self::CONDITION_AND): void
    {
        // works only AND condition

        switch ($condition) {
            case DataSourceInterface::CONDITION_AND:
                $this->queryBuilder->addFilter($expression);
                break;
            case DataSourceInterface::CONDITION_OR:
                $this->queryBuilder->orWhere($expression);
                break;
        }
    }

    public function getExpressionBuilder(): ExpressionBuilderInterface
    {
        return $this->expressionBuilder;
    }

    public function getData(Parameters $parameters)
    {
        $adapter = new JsonAdapter($this->queryBuilder, $this->curlAdapter);
        $paginator = new Pagerfanta($adapter);
        $paginator->setNormalizeOutOfRangePages(true);
        $paginator->setAllowOutOfRangePages(true);
        $paginator->setCurrentPage($parameters->get('page', 1));
        return $paginator;
    }
}