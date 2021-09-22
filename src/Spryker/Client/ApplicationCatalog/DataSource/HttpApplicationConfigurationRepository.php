<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog\DataSource;

use Generated\Shared\Transfer\ApplicationConfigurationRequestTransfer;
use Generated\Shared\Transfer\ApplicationConfigurationResponseTransfer;
use Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig;
use Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface;
use Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface;
use Spryker\Client\ApplicationCatalog\Http\Exception\ApplicationCatalogHttpRequestException;
use Spryker\Service\UtilText\Model\Url\Url;

class HttpApplicationConfigurationRepository implements ApplicationConfigurationRepositoryInterface
{
    /**
     * @var string
     */
    protected const HTTP_REQUEST_METHOD = 'GET';

    /**
     * @var \Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig
     */
    protected $applicationCatalogConfig;

    /**
     * @param \Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface $httpClient
     * @param \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig $applicationCatalogConfig
     */
    public function __construct(
        ApplicationCatalogToHttpClientAdapterInterface $httpClient,
        ApplicationCatalogToUtilEncodingServiceInterface $utilEncodingService,
        ApplicationCatalogConfig $applicationCatalogConfig
    ) {
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
        $this->applicationCatalogConfig = $applicationCatalogConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationConfigurationRequestTransfer $applicationConfigRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationConfigurationResponseTransfer
     */
    public function getApplicationConfiguration(
        ApplicationConfigurationRequestTransfer $applicationConfigRequestTransfer
    ): ApplicationConfigurationResponseTransfer {
        $configData = $this->getApplicationCatalogConfigurationDataFromSource(
            'application/' . $applicationConfigRequestTransfer->getApplicationUuidOrFail(),
            $applicationConfigRequestTransfer->toArray(false, true)
        );

        return (new ApplicationConfigurationResponseTransfer())->fromArray($configData, true);
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function getApplicationCatalogConfigurationDataFromSource(string $endpoint, array $params): array
    {
        try {
            $url = Url::generate($this->applicationCatalogConfig->getDataSourceUrl() . $endpoint, $params)->build();
            $data = (string)$this->httpClient->request(static::HTTP_REQUEST_METHOD, $url)->getBody();
        } catch (ApplicationCatalogHttpRequestException $applicationCatalogHttpRequestException) {
            return [];
        }

        if (!$data) {
            return [];
        }

        return array_merge($this->utilEncodingService->decodeJson($data, true), $params);
    }
}
