<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;


final class CurlAdapter implements CurlAdapterInterface
{
    /** @var array */
    private $headers;

    public function __construct(array $headers)
    {
        $this->headers = $headers;
    }

    public function prepareCurl()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);
        foreach ($this->headers[0] as $key => $header) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [$key . ": " . $header]);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);  # connection timeout 2 seconds
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); # curl -L key
        return $ch;
    }

}