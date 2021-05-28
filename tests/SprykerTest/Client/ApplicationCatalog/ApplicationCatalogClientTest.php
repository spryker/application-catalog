<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ApplicationCatalog;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LabelCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Client
 * @group ApplicationCatalog
 * @group ApplicationCatalogClientTest
 * Add your own group annotations below this line
 */
class ApplicationCatalogClientTest extends Test
{
    protected const LOCAL_NAME = 'en_US';

    /**
     * @phpstan-var mixed
     *
     * @var \SprykerTest\Client\ApplicationCatalog\ApplicationCatalogClientTester
     */
    protected $tester;

    /**
     * @dataProvider searchTermDataProvider()
     *
     * @param int $expectedCount
     * @param string|null $searchTerm
     * @param array|null $categoryIds
     * @param array|null $labelIds
     *
     * @return void
     */
    public function testGetApplicationCollectionShouldReturnCollectionTransfer(
        int $expectedCount,
        ?string $searchTerm,
        ?array $categoryIds,
        ?array $labelIds
    ): void {
        $applicationCatalogClient = $this->tester->getClient();

        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setSearchTerm($searchTerm)
            ->setCategoryIds($categoryIds)
            ->setLabelIds($labelIds)
            ->setLocale($this->getLocaleTransfer());
        $applicationCollectionTransfer = $applicationCatalogClient->getApplicationCollection($applicationCriteriaTransfer);

        $applicationCount = $applicationCollectionTransfer->getApplications()->count();
        $this->assertEquals($expectedCount, $applicationCount);

        foreach ($applicationCollectionTransfer->getApplications() as $applicationTransfer) {
            $this->checkApplicationTransfer($applicationTransfer);
        }
    }

    /**
     * @return array[]
     */
    public function searchTermDataProvider(): array
    {
        return [
            'search by name with null' => [1, 'one', null, null],
            'search by name with empty array' => [1, 'one', [], []],
            'search by providerName and label' => [1, 'Payone', [], ['new']],
            'search by category and providerName with register' => [1, 'payone', ['payment'], null],
            'search by full criteria' => [1, 'device', ['commerce'], ['gold', 'test']],
            'search not set' => [1, null, null, null],
            'wrong searchTerm' => [0, 'qwer', null, null],
            'wrong searchTerm with label and category' => [0, 'qwer', ['payment', 1], ['gold']],
            'search by category and label' => [1, null, ['payment'], ['gold']],
            'empty criteria' => [1, '', [], null],
            'criteria with empty string' => [0, '', [''], ['']],
        ];
    }

    /**
     * @return void
     */
    public function testGetAppDetailsShouldReturnTransfer(): void
    {
        $applicationCatalogClient = $this->tester->getClient();

        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setIdApplication('payment-provider-payone')
            ->setLocale($this->getLocaleTransfer());
        $applicationTransfer = $applicationCatalogClient->findApplication($applicationCriteriaTransfer);

        $this->checkApplicationTransfer($applicationTransfer);
    }

    /**
     * @return void
     */
    public function testGetAppDetailsShouldReturnNull(): void
    {
        $applicationCatalogClient = $this->tester->getClient();

        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setIdApplication('test-unreal-app')
            ->setLocale($this->getLocaleTransfer());
        $applicationTransfer = $applicationCatalogClient->findApplication($applicationCriteriaTransfer);

        $this->assertNull($applicationTransfer);
    }

    /**
     * @return void
     */
    public function testGetAppCategoriesShouldReturnCollectionTransfer(): void
    {
        $applicationCatalogClient = $this->tester->getClient();

        $applicationCategoryTransfer = (new ApplicationCategoryCriteriaTransfer())->setLocale($this->getLocaleTransfer());
        $applicationCategoryCollectionTransfer = $applicationCatalogClient->getCategoryCollection($applicationCategoryTransfer);
        foreach ($applicationCategoryCollectionTransfer->getCategories() as $applicationCategoryTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationCategoryTransfer', $applicationCategoryTransfer);
            $this->assertNotEmpty($applicationCategoryTransfer->getName());
        }
    }

    /**
     * @return void
     */
    public function testGetLabelsShouldReturnCollectionTransfer(): void
    {
        $applicationCatalogClient = $this->tester->getClient();

        $labelCriteriaTransfer = (new LabelCriteriaTransfer())->setLocale($this->getLocaleTransfer());
        $labelCollectionTransfer = $applicationCatalogClient->getLabelCollection($labelCriteriaTransfer);
        foreach ($labelCollectionTransfer->getLabels() as $labelTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\LabelTransfer', $labelTransfer);
            $this->assertNotEmpty($labelTransfer->getName());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationTransfer $applicationTransfer
     *
     * @return void
     */
    protected function checkApplicationTransfer(ApplicationTransfer $applicationTransfer): void
    {
        $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationTransfer', $applicationTransfer);
        $this->assertNotEmpty($applicationTransfer->getProviderName());
        $this->assertNotEmpty($applicationTransfer->getName());
        $this->assertNotEmpty($applicationTransfer->getIconUrl());
        $this->assertNotEmpty($applicationTransfer->getDescription());
        $this->assertNotEmpty($applicationTransfer->getUrl());
        $this->assertNotEmpty($applicationTransfer->getRating());
        $this->assertNotEmpty($applicationTransfer->getTotalReviews());

        foreach ($applicationTransfer->getCategories() as $applicationCategoryTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationCategoryTransfer', $applicationCategoryTransfer);
        }

        foreach ($applicationTransfer->getLabels() as $labelTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\LabelTransfer', $labelTransfer);
        }

        foreach ($applicationTransfer->getGalleryItems() as $galleryItemTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\GalleryItemTransfer', $galleryItemTransfer);
        }

        foreach ($applicationTransfer->getResources() as $resourceTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\ResourceItemTransfer', $resourceTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(): LocaleTransfer
    {
        return (new LocaleTransfer())->setLocaleName(static::LOCAL_NAME);
    }
}
