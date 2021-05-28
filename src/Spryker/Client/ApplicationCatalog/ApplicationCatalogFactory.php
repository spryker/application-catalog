<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog;

use Spryker\Client\ApplicationCatalog\DataSource\ApplicationCatalogRepositoryInterface;
use Spryker\Client\ApplicationCatalog\DataSource\FileApplicationCatalogRepository;
use Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ApplicationCatalogFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ApplicationCatalog\DataSource\ApplicationCatalogRepositoryInterface
     */
    public function createApplicationCatalogRepository(): ApplicationCatalogRepositoryInterface
    {
        return new FileApplicationCatalogRepository(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ApplicationCatalogToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ApplicationCatalogDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
