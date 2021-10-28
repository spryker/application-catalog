<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Client\ApplicationCatalog;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AdvertisementBannerCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationConnectRequestTransfer;
use Generated\Shared\Transfer\ApplicationCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LabelCriteriaTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;
use Psr\Http\Message\StreamInterface;
use Spryker\Client\ApplicationCatalog\ApplicationCatalogDependencyProvider;
use Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface;
use Spryker\Client\ApplicationCatalog\Http\Exception\ApplicationCatalogHttpRequestException;

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
    /**
     * @var string
     */
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
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('applications.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())->setLocale($this->getLocaleTransfer());

        // Act
        $applicationCollectionTransfer = $this->tester->getClient()->getApplicationCollection($applicationCriteriaTransfer);

        // Assert
        $this->assertGreaterThan(0, $applicationCollectionTransfer->getApplications()->count());
    }

    /**
     * @return void
     */
    public function testFindApplicationShouldReturnTransfer(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('application.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setApplicationUuid('payment-provider-payone')
            ->setLocale($this->getLocaleTransfer());

        // Act
        $applicationTransfer = $this->tester->getClient()->findApplication($applicationCriteriaTransfer);

        // Assert
        $this->assertInstanceOf(ApplicationTransfer::class, $applicationTransfer);
    }

    /**
     * @return void
     */
    public function testFindApplicationShouldReturnNull(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock('');
        $this->httpClient->method('request')->willReturn($responseMock);
        $applicationCriteriaTransfer = (new ApplicationCriteriaTransfer())
            ->setApplicationUuid('test-unreal-app')
            ->setLocale($this->getLocaleTransfer());

        // Act
        $applicationTransfer = $this->tester->getClient()->findApplication($applicationCriteriaTransfer);

        // Assert
        $this->assertNull($applicationTransfer);
    }

    /**
     * @return void
     */
    public function testGetCategoryCollectionShouldReturnCollectionTransfer(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('categories.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $applicationCategoryTransfer = (new ApplicationCategoryCriteriaTransfer())->setLocale($this->getLocaleTransfer());

        // Act
        $applicationCategoryCollectionTransfer = $this->tester
            ->getClient()
            ->getCategoryCollection($applicationCategoryTransfer);

        // Assert
        $this->assertGreaterThan(0, $applicationCategoryCollectionTransfer->getCategories()->count());
    }

    /**
     * @return void
     */
    public function testGetLabelCollectionShouldReturnCollectionTransfer(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('labels.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $labelCriteriaTransfer = (new LabelCriteriaTransfer())->setLocale($this->getLocaleTransfer());

        // Act
        $labelCollectionTransfer = $this->tester->getClient()->getLabelCollection($labelCriteriaTransfer);

        // Assert
        $this->assertGreaterThan(0, $labelCollectionTransfer->getLabels()->count());
    }

    /**
     * @return void
     */
    public function testGetAdvertisementBannerCollectionShouldReturnAdvertisementBannerCollectionTransfer(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('advertisementBanners.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $advertisementBannerCriteriaTransfer = new AdvertisementBannerCriteriaTransfer();

        // Act
        $advertisementBannerCollectionTransfer = $this->tester->getClient()
            ->getAdvertisementBannerCollection($advertisementBannerCriteriaTransfer);

        // Assert
        $this->assertGreaterThan(0, $advertisementBannerCollectionTransfer->getAdvertisementBanners()->count());
    }

    /**
     * @return void
     */
    public function testGetAdvertisementBannerCollectionReturnsAnEmptyAdvertisementBannerCollectionWhenCriteriaNotMatchesAnyBanner(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('advertisementBannersEmpty.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $advertisementBannerCriteriaTransfer = new AdvertisementBannerCriteriaTransfer();

        // Act
        $advertisementBannerCollectionTransfer = $this->tester
            ->getClient()
            ->getAdvertisementBannerCollection($advertisementBannerCriteriaTransfer);

        // Assert
        $this->assertEquals(0, $advertisementBannerCollectionTransfer->getAdvertisementBanners()->count());
    }

    /**
     * @return void
     */
    public function testGetAdvertisementBannerCollectionReturnsAnEmptyAdvertisementBannerCollectionWhenHttpClientExceptionIsThrown(): void
    {
        // Arrange
        $this->httpClient->method('request')->willThrowException(new ApplicationCatalogHttpRequestException());
        $advertisementBannerCriteriaTransfer = new AdvertisementBannerCriteriaTransfer();

        // Act
        $advertisementBannerCollectionTransfer = $this->tester
            ->getClient()
            ->getAdvertisementBannerCollection($advertisementBannerCriteriaTransfer);

        // Assert
        $this->assertEquals(0, $advertisementBannerCollectionTransfer->getAdvertisementBanners()->count());
    }

    /**
     * @return void
     */
    public function testConnectApplicationShouldReturnApplicationConnectResponseTransferWithTrueConnectedStatus(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('connect.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $applicationConnectRequestTransfer = new ApplicationConnectRequestTransfer();

        // Act
        $applicationConnectResponseTransfer = $this->tester
            ->getClient()
            ->connectApplication($applicationConnectRequestTransfer);

        // Assert
        $this->assertEquals(true, $applicationConnectResponseTransfer->getIsConnected());
    }

    /**
     * @return void
     */
    public function testConnectApplicationShouldReturnApplicationConnectResponseTransferWithFalseConnectedStatus(): void
    {
        // Arrange
        $responseMock = $this->getResponseMock($this->getFixture('connectUnsuccessful.json'));
        $this->httpClient->method('request')->willReturn($responseMock);
        $applicationConnectRequestTransfer = new ApplicationConnectRequestTransfer();

        // Act
        $applicationConnectResponseTransfer = $this->tester
            ->getClient()
            ->connectApplication($applicationConnectRequestTransfer);

        // Assert
        $this->assertEquals(false, $applicationConnectResponseTransfer->getIsConnected());
    }

    /**
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    protected function getLocaleTransfer(): LocaleTransfer
    {
        return (new LocaleTransfer())->setLocaleName(static::LOCAL_NAME);
    }

    /**
     * @param string $responseBody
     *
     * @return \GuzzleHttp\Psr7\Response|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getResponseMock(string $responseBody): GuzzleHttpResponse
    {
        $responseMock = $this->createMock(GuzzleHttpResponse::class);
        $streamMock = $this->createMock(StreamInterface::class);

        $streamMock->method('getContents')
            ->willReturn($responseBody);
        $responseMock->method('getBody')
            ->willReturn($streamMock);

        return $responseMock;
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
