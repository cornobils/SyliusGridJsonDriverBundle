<?php

declare(strict_types=1);

namespace Sylius\Bundle\GridBundle\Driver\Json\Json;


interface CurlAdapterInterface
{
    public function prepareCurl();
}