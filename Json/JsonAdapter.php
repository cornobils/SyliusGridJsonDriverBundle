<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;


use Pagerfanta\Adapter\AdapterInterface;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

final class JsonAdapter implements AdapterInterface
{
    private $url;

    private $array;

    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->url = (string) $queryBuilder;
    }

    public function createCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json', 'Authorization: Bearer SampleToken']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);  # connection timeout 2 seconds
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); # curl -L key
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