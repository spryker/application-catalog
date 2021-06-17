<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ApplicationCatalog;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationCriteriaTransfer;
use Generated\Shared\Transfer\LabelCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use GuzzleHttp\Psr7\Response;
use Spryker\Client\ApplicationCatalog\ApplicationCatalogDependencyProvider;
use Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface;

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
     * @var \Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $httpClient;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(ApplicationCatalogToHttpClientAdapterInterface::class);

        $this->tester->setDependency(
            ApplicationCatalogDependencyProvider::CLIENT_HTTP,
            $this->httpClient
        );
    }

    /**
     * @return void
     */
    public function testGetApplicationCollectionShouldReturnCollectionTransfer(): void
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($this->getFixture('applications.json'));
        $this->httpClient->method('request')->willReturn($response);

        $applicationCatalogClient = $this->tester->getClient();
        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())->setLocale($this->getLocaleTransfer());
        $applicationCollectionTransfer = $applicationCatalogClient->getApplicationCollection($applicationCriteriaTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationCollectionTransfer', $applicationCollectionTransfer);
        $this->assertGreaterThan(0, $applicationCollectionTransfer->getApplications()->count());

        foreach ($applicationCollectionTransfer->getApplications() as $applicationTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationTransfer', $applicationTransfer);
        }
    }

    /**
     * @return void
     */
    public function testFindApplicationShouldReturnTransfer(): void
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($this->getFixture('application.json'));
        $this->httpClient->method('request')->willReturn($response);

        $applicationCatalogClient = $this->tester->getClient();

        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setApplicationUuid('payment-provider-payone')
            ->setLocale($this->getLocaleTransfer());
        $applicationTransfer = $applicationCatalogClient->findApplication($applicationCriteriaTransfer);

        $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationTransfer', $applicationTransfer);
    }

    /**
     * @return void
     */
    public function testFindApplicationShouldReturnNull(): void
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn('');
        $this->httpClient->method('request')->willReturn($response);

        $applicationCatalogClient = $this->tester->getClient();

        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setApplicationUuid('test-unreal-app')
            ->setLocale($this->getLocaleTransfer());
        $applicationTransfer = $applicationCatalogClient->findApplication($applicationCriteriaTransfer);

        $this->assertNull($applicationTransfer);
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionShouldReturnCollectionTransfer(): void
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($this->getFixture('categories.json'));
        $this->httpClient->method('request')->willReturn($response);

        $applicationCatalogClient = $this->tester->getClient();

        $applicationCategoryTransfer = (new ApplicationCategoryCriteriaTransfer())->setLocale($this->getLocaleTransfer());
        $applicationCategoryCollectionTransfer = $applicationCatalogClient->getCategoryCollection($applicationCategoryTransfer);

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\ApplicationCategoryCollectionTransfer',
            $applicationCategoryCollectionTransfer
        );
        $this->assertGreaterThan(0, $applicationCategoryCollectionTransfer->getCategories()->count());

        foreach ($applicationCategoryCollectionTransfer->getCategories() as $applicationCategoryTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\ApplicationCategoryTransfer', $applicationCategoryTransfer);
        }
    }

    /**
     * @return void
     */
    public function testGetLabelCollectionShouldReturnCollectionTransfer(): void
    {
        $response = $this->createMock(Response::class);
        $response->method('getBody')->willReturn($this->getFixture('labels.json'));
        $this->httpClient->method('request')->willReturn($response);

        $applicationCatalogClient = $this->tester->getClient();

        $labelCriteriaTransfer = (new LabelCriteriaTransfer())->setLocale($this->getLocaleTransfer());
        $labelCollectionTransfer = $applicationCatalogClient->getLabelCollection($labelCriteriaTransfer);

        $this->assertInstanceOf(
            '\Generated\Shared\Transfer\LabelCollectionTransfer',
            $labelCollectionTransfer
        );
        $this->assertGreaterThan(0, $labelCollectionTransfer->getLabels()->count());

        foreach ($labelCollectionTransfer->getLabels() as $labelTransfer) {
            $this->assertInstanceOf('\Generated\Shared\Transfer\LabelTransfer', $labelTransfer);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(): LocaleTransfer
    {
        return (new LocaleTransfer())->setLocaleName(static::LOCAL_NAME);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    protected function getFixture(string $fileName): string
    {
        return file_get_contents(codecept_data_dir('Fixtures/' . $fileName));
    }
}
