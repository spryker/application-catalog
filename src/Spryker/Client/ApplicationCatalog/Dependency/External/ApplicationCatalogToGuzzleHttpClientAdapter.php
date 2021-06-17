<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog\Dependency\External;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Spryker\Client\ApplicationCatalog\Http\Exception\ApplicationCatalogHttpRequestException;

class ApplicationCatalogToGuzzleHttpClientAdapter implements ApplicationCatalogToHttpClientAdapterInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $method
     * @param string $uri
     * @param mixed[] $options
     *
     * @throws \Spryker\Client\ApplicationCatalog\Http\Exception\ApplicationCatalogHttpRequestException
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function request(string $method, string $uri, array $options = []): ResponseInterface
    {
        try {
            return $this->httpClient->request($method, $uri, $options);
        } catch (GuzzleException $exception) {
            throw new ApplicationCatalogHttpRequestException(
                $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }
}
