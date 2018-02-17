<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;


use Pagerfanta\Adapter\ArrayAdapter;

final class JsonAdapter extends ArrayAdapter
{
    public function __construct(QueryBuilder $queryBuilder)
    {
        $res = $this->createCurl((string) $queryBuilder);
        parent::__construct($res);
    }

    public function createCurl(string $configuration)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $configuration);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json', 'Authorization: Bearer SampleToken']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);  # connection timeout 2 seconds
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); # curl -L key
        $response = curl_exec($ch);

        $res = json_decode($response, true);
        curl_close($ch);
        return $res['_embedded']['items'];
    }
}