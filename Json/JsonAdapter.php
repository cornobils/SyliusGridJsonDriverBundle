<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;


use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

final class JsonAdapter implements AdapterInterface
{
    private $url;

    private $array;

    /** @var CurlAdapterInterface */
    private $curlAdapter;

    public function __construct(QueryBuilder $queryBuilder, CurlAdapterInterface $curlAdapter)
    {
        $this->url = (string) $queryBuilder;
        $this->curlAdapter = $curlAdapter;
    }

    public function createCurl()
    {
        $ch = $this->curlAdapter->prepareCurl();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        $response = curl_exec($ch);
        $res = json_decode($response, true);
        curl_close($ch);
        if (empty($res)) {
            throw new ServiceUnavailableHttpException();
        }
        if (array_key_exists('code', $res)) {
            throw new ServiceUnavailableHttpException(null, 'On server side: '. $res['message']);
        }
        $this->array = $res;
    }

    public function getSlice($offset, $length):array
    {
        $page = ceil($offset / $length) + 1; # because Pagerfanta::calculateOffsetForCurrentPageResults() makes -1
        $this->url = $this->url . "&limit=" . $length . "&page=" . $page;
        $this->createCurl();
        return $this->array['_embedded']['items'];
    }

    public function getNbResults()
    {
        return $this->array['total'];
    }

}