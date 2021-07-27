<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog;

use Generated\Shared\Transfer\AdvertisementBannerCollectionTransfer;
use Generated\Shared\Transfer\AdvertisementBannerCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationCategoryCollectionTransfer;
use Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationCollectionTransfer;
use Generated\Shared\Transfer\ApplicationConnectRequestTransfer;
use Generated\Shared\Transfer\ApplicationConnectResponseTransfer;
use Generated\Shared\Transfer\ApplicationCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LabelCollectionTransfer;
use Generated\Shared\Transfer\LabelCriteriaTransfer;

interface ApplicationCatalogClientInterface
{
    /**
     * Specification:
     * - Returns an application by provided identifier.
     * - Returns null if nothing found.
     * - Requires ApplicationDetailsCriteria.applicationUuid transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationTransfer|null
     */
    public function findApplication(
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): ?ApplicationTransfer;

    /**
     * Specification:
     * - Returns a collection of applications by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationCollectionTransfer
     */
    public function getApplicationCollection(
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): ApplicationCollectionTransfer;

    /**
     * Specification:
     * - Returns a collection of categories by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer $applicationCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationCategoryCollectionTransfer
     */
    public function getCategoryCollection(
        ApplicationCategoryCriteriaTransfer $applicationCategoryCriteriaTransfer
    ): ApplicationCategoryCollectionTransfer;

    /**
     * Specification:
     * - Returns a collection of labels by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LabelCriteriaTransfer $labelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\LabelCollectionTransfer
     */
    public function getLabelCollection(
        LabelCriteriaTransfer $labelCriteriaTransfer
    ): LabelCollectionTransfer;

    /**
     * Specification:
     * - Returns a collection of advertisement banners by provided criteria.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AdvertisementBannerCriteriaTransfer $advertisementBannerCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\AdvertisementBannerCollectionTransfer
     */
    public function getAdvertisementBannerCollection(
        AdvertisementBannerCriteriaTransfer $advertisementBannerCriteriaTransfer
    ): AdvertisementBannerCollectionTransfer;

    /**
     * Specification:
     * - Connects application
     * - Requires ApplicationDetailsCriteria.applicationUuid transfer field to be set.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApplicationConnectRequestTransfer $applicationConnectRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationConnectResponseTransfer
     */
    public function connectApplication(ApplicationConnectRequestTransfer $applicationConnectRequestTransfer): ApplicationConnectResponseTransfer;
}
