<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog;

use Spryker\Client\Kernel\AbstractBundleConfig;

class ApplicationCatalogConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const DATA_SOURCE_URL = 'http://registry.pbc.spryker.local/app-store/';

    /**
     * @api
     *
     * @return string
     */
    public function getDataSourceUrl(): string
    {
        return static::DATA_SOURCE_URL;
    }
}
