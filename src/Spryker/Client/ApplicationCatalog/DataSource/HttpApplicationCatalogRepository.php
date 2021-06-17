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
use Generated\Shared\Transfer\ApplicationConnectRequestTransfer;
use Generated\Shared\Transfer\ApplicationConnectResponseTransfer;
use Generated\Shared\Transfer\ApplicationCriteriaTransfer;
use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\LabelCollectionTransfer;
use Generated\Shared\Transfer\LabelCriteriaTransfer;
use Generated\Shared\Transfer\LabelTransfer;
use Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig;
use Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface;
use Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface;
use Spryker\Client\ApplicationCatalog\Http\Exception\ApplicationCatalogHttpRequestException;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Log\LoggerTrait;

class HttpApplicationCatalogRepository implements ApplicationCatalogRepositoryInterface
{
    use LoggerTrait;

    protected const HTTP_REQUEST_METHOD = 'GET';

    /**
     * @var \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig
     */
    protected $applicationCatalogConfig;

    /**
     * @param \Spryker\Client\ApplicationCatalog\Dependency\Service\ApplicationCatalogToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Client\ApplicationCatalog\Dependency\External\ApplicationCatalogToHttpClientAdapterInterface $httpClient
     * @param \Spryker\Client\ApplicationCatalog\ApplicationCatalogConfig $applicationCatalogConfig
     */
    public function __construct(
        ApplicationCatalogToUtilEncodingServiceInterface $utilEncodingService,
        ApplicationCatalogToHttpClientAdapterInterface $httpClient,
        ApplicationCatalogConfig $applicationCatalogConfig
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->httpClient = $httpClient;
        $this->applicationCatalogConfig = $applicationCatalogConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationCriteriaTransfer $applicationCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationCollectionTransfer
     */
    public function getApplicationCollection(
        ApplicationCriteriaTransfer $applicationCriteriaTransfer
    ): ApplicationCollectionTransfer {
        $applicationsData = $this->getDataFromSource('applications', [
            'locale' => $applicationCriteriaTransfer->getLocaleOrFail()->getLocaleName(),
        ]);

        $applicationCollectionTransfer = new ApplicationCollectionTransfer();
        foreach ($applicationsData as $applicationData) {
            $applicationTransfer = (new ApplicationTransfer())->fromArray($applicationData, true);
            $applicationCollectionTransfer->addApplication($applicationTransfer);
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
        $applicationData = $this->getDataFromSource('application/' . $applicationCriteriaTransfer->getApplicationUuid(), [
            'locale' => $applicationCriteriaTransfer->getLocaleOrFail()->getLocaleName(),
        ]);

        if (!$applicationData) {
            return null;
        }

        return (new ApplicationTransfer())->fromArray($applicationData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationCategoryCriteriaTransfer $applicationCategoryCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationCategoryCollectionTransfer
     */
    public function getCategoryCollection(
        ApplicationCategoryCriteriaTransfer $applicationCategoryCriteriaTransfer
    ): ApplicationCategoryCollectionTransfer {
        $categoriesData = $this->getDataFromSource('categories', [
            'locale' => $applicationCategoryCriteriaTransfer->getLocaleOrFail()->getLocaleName(),
        ]);

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
        $labelsData = $this->getDataFromSource('labels', [
            'locale' => $labelCriteriaTransfer->getLocaleOrFail()->getLocaleName(),
        ]);

        $labelCollectionTransfer = new LabelCollectionTransfer();
        foreach ($labelsData as $labelData) {
            $labelTransfer = (new LabelTransfer())->fromArray($labelData, true);
            $labelCollectionTransfer->addLabel($labelTransfer);
        }

        return $labelCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApplicationConnectRequestTransfer $applicationConnectRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApplicationConnectResponseTransfer
     */
    public function connectApplication(ApplicationConnectRequestTransfer $applicationConnectRequestTransfer): ApplicationConnectResponseTransfer
    {
        $connectionData = $this->getDataFromSource('connect/' . $applicationConnectRequestTransfer->getApplicationUuid(), []);
        $isConnected = !empty($connectionData['intentionUuid']);

        return (new ApplicationConnectResponseTransfer())->setIsConnected($isConnected);
    }

    /**
     * @param string $endpoint
     * @param mixed[] $params
     *
     * @return mixed[]
     */
    protected function getDataFromSource(string $endpoint, array $params): array
    {
        try {
            $url = Url::generate($this->applicationCatalogConfig->getDataSourceUrl() . $endpoint, $params)->build();
            $data = (string)$this->httpClient->request(static::HTTP_REQUEST_METHOD, $url)->getBody();
        } catch (ApplicationCatalogHttpRequestException $applicationCatalogHttpRequestException) {
            $this->logHttpApplicationCatalogError($applicationCatalogHttpRequestException);

            return [];
        }

        if (!$data) {
            return [];
        }

        return $this->utilEncodingService->decodeJson($data, true);
    }

    /**
     * @param \Spryker\Client\ApplicationCatalog\Http\Exception\ApplicationCatalogHttpRequestException $applicationCatalogHttpRequestException
     *
     * @return void
     */
    protected function logHttpApplicationCatalogError(
        ApplicationCatalogHttpRequestException $applicationCatalogHttpRequestException
    ): void {
        $this->getLogger()->error(
            $applicationCatalogHttpRequestException->getMessage(),
            ['exception' => $applicationCatalogHttpRequestException]
        );
    }
}
