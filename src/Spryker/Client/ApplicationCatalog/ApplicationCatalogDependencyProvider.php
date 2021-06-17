<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog;

use GuzzleHttp\Client as GuzzleHttpClient;
use Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToGuzzleHttpClientAdapter;
use Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

/**
 * @method \Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig getConfig()
 */
class ApplicationCatalogDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';
    public const CLIENT_HTTP = 'CLIENT_HTTP';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addHttpClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ApplicationCatalogToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addHttpClient(Container $container): Container
    {
        $container->set(static::CLIENT_HTTP, function () {
            return new ApplicationCatalogToGuzzleHttpClientAdapter(
                new GuzzleHttpClient()
            );
        });

        return $container;
    }
}
