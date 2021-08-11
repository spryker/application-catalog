<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog;

use Spryker\Client\ApplicationCatalog\DataSource\ApplicationCatalogRepositoryInterface;
use Spryker\Client\ApplicationCatalog\DataSource\ApplicationConfigurationRepositoryInterface;
use Spryker\Client\ApplicationCatalog\DataSource\HttpApplicationCatalogRepository;
use Spryker\Client\ApplicationCatalog\DataSource\HttpApplicationConfigurationRepository;
use Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface;
use Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig getConfig()
 */
class ApplicationCatalogFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ApplicationCatalog\DataSource\ApplicationCatalogRepositoryInterface
     */
    public function createApplicationCatalogRepository(): ApplicationCatalogRepositoryInterface
    {
        return new HttpApplicationCatalogRepository(
            $this->getUtilEncodingService(),
            $this->getHttpClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\ApplicationCatalog\DataSource\ApplicationConfigurationRepositoryInterface
     */
    public function createApplicationConfigRepository(): ApplicationConfigurationRepositoryInterface
    {
        return new HttpApplicationConfigurationRepository(
            $this->getHttpClient(),
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ApplicationCatalogToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ApplicationCatalogDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface
     */
    public function getHttpClient(): ApplicationCatalogToHttpClientAdapterInterface
    {
        return $this->getProvidedDependency(ApplicationCatalogDependencyProvider::CLIENT_HTTP);
    }
}
