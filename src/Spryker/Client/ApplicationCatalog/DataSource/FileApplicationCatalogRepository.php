<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ApplicationCatalog\DataSource;

use Generated\Shared\Transfer\ApplicationCategoryCollectionTransfer;
use Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationCategoryTransfer;
use Generated\Shared\Transfer\ApplicationCollectionTransfer;
use Generated\Shared\Transfer\ApplicationCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LabelCollectionTransfer;
use Generated\Shared\Transfer\LabelCriteriaTransfer;
use Generated\Shared\Transfer\LabelTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface;

class FileApplicationCatalogRepository implements ApplicationCatalogRepositoryInterface
{
    protected const FILE_PATH = __DIR__ . '/../../../../../data/';

    protected const APPS_FILE = 'apps.json';
    protected const APP_DETAIL_FILE = 'app-details.json';
    protected const APP_CATEGORIES_FILE = 'categories.json';
    protected const APP_LABELS_FILE = 'labels.json';

    /**
     * @var \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(ApplicationCatalogToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationCollectionTransfer
     */
    public function getApplicationCollection(
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): ApplicationCollectionTransfer {
        $applicationsData = $this->getDataFromFile(
            static::APPS_FILE,
            $applicationCriteriaTransfer->getLocale()
        );

        $applicationCollectionTransfer = new ApplicationCollectionTransfer();
        foreach ($applicationsData as $applicationData) {
            $applicationTransfer = (new ApplicationTransfer())->fromArray($applicationData, true);

            if ($this->isApplicationMatchWithCriteria($applicationTransfer, $applicationCriteriaTransfer)) {
                $applicationCollectionTransfer->addApplication($applicationTransfer);
            }
        }

        return $applicationCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationTransfer|null
     */
    public function findApplication(
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): ?ApplicationTransfer {
        $applicationData = $this->getDataFromFile(
            static::APP_DETAIL_FILE,
            $applicationCriteriaTransfer->getLocale()
        );

        $applicationTransfer = (new ApplicationTransfer())->fromArray($applicationData, true);

        if ($applicationCriteriaTransfer->getIdApplication() === $applicationTransfer->getIdApplication()) {
            return $applicationTransfer;
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer $applicationCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationCategoryCollectionTransfer
     */
    public function getCategoryCollection(
        ApplicationCategoryCriteriaTransfer $applicationCategoryCriteriaTransfer
    ): ApplicationCategoryCollectionTransfer {
        $categoriesData = $this->getDataFromFile(
            static::APP_CATEGORIES_FILE,
            $applicationCategoryCriteriaTransfer->getLocale()
        );

        $applicationCategoryCollectionTransfer = new ApplicationCategoryCollectionTransfer();
        foreach ($categoriesData as $categoryData) {
            $applicationCategoryTransfer = (new ApplicationCategoryTransfer())->fromArray($categoryData, true);
            $applicationCategoryCollectionTransfer->addCategory($applicationCategoryTransfer);
        }

        return $applicationCategoryCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\LabelCriteriaTransfer $labelCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\LabelCollectionTransfer
     */
    public function getLabelCollection(
        LabelCriteriaTransfer $labelCriteriaTransfer
    ): LabelCollectionTransfer {
        $labelsData = $this->getDataFromFile(
            static::APP_LABELS_FILE,
            $labelCriteriaTransfer->getLocale()
        );

        $labelCollectionTransfer = new LabelCollectionTransfer();
        foreach ($labelsData as $labelData) {
            $labelTransfer = (new LabelTransfer())->fromArray($labelData, true);
            $labelCollectionTransfer->addLabel($labelTransfer);
        }

        return $labelCollectionTransfer;
    }

    /**
     * @param string $fileName
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return mixed[]
     */
    protected function getDataFromFile(string $fileName, LocaleTransfer $localeTransfer): array
    {
        $applicationsData = file_get_contents(
            static::FILE_PATH . $localeTransfer->getLocaleName() . DIRECTORY_SEPARATOR . $fileName
        );

        if (!$applicationsData) {
            return [];
        }

        return $this->utilEncodingService->decodeJson($applicationsData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return bool
     */
    protected function isApplicationMatchWithCriteria(
        ApplicationTransfer $applicationTransfer,
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): bool {
        return $this->isApplicationMatchWithSearch($applicationTransfer, $applicationCriteriaTransfer)
            && $this->isApplicationMatchWithCategory($applicationTransfer, $applicationCriteriaTransfer)
            && $this->isApplicationMatchWithLabel($applicationTransfer, $applicationCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return bool
     */
    protected function isApplicationMatchWithSearch(
        ApplicationTransfer $applicationTransfer,
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): bool {
        if (!$applicationCriteriaTransfer->getSearchTerm()) {
            return true;
        }

        if (mb_stripos($applicationTransfer->getName(), $applicationCriteriaTransfer->getSearchTerm()) !== false) {
            return true;
        }

        if (mb_stripos($applicationTransfer->getProviderName(), $applicationCriteriaTransfer->getSearchTerm()) !== false) {
            return true;
        }

        if (mb_stripos($applicationTransfer->getDescription(), $applicationCriteriaTransfer->getSearchTerm()) !== false) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return bool
     */
    protected function isApplicationMatchWithCategory(
        ApplicationTransfer $applicationTransfer,
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): bool {
        if (!$applicationCriteriaTransfer->getCategoryIds()) {
            return true;
        }

        foreach ($applicationTransfer->getCategories() as $applicationCategoryTransfer) {
            if (in_array($applicationCategoryTransfer->getIdCategory(), $applicationCriteriaTransfer->getCategoryIds())) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return bool
     */
    protected function isApplicationMatchWithLabel(
        ApplicationTransfer $applicationTransfer,
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): bool {
        if (!$applicationCriteriaTransfer->getLabelIds()) {
            return true;
        }

        foreach ($applicationTransfer->getLabels() as $labelTransfer) {
            if (in_array($labelTransfer->getIdLabel(), $applicationCriteriaTransfer->getLabelIds())) {
                return true;
            }
        }

        return false;
    }
}
