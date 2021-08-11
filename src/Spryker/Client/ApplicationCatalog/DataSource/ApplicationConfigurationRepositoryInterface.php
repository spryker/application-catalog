<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog\DataSource;

use Generated\Shared\Transfer\ApplicationConfigurationRequestTransfer;
use Generated\Shared\Transfer\ApplicationConfigurationResponseTransfer;

interface ApplicationConfigurationRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApplicationConfigurationRequestTransfer $applicationConfigRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationConfigurationResponseTransfer
     */
    public function getApplicationConfiguration(
        ApplicationConfigurationRequestTransfer $applicationConfigRequestTransfer
    ): ApplicationConfigurationResponseTransfer;
}
