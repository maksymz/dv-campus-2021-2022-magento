<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Controller\Index;

use DVCampus\PersonalDiscount\Controller\InvalidFormRequestException;
use DVCampus\PersonalDiscount\Model\DiscountRequest;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Store\Model\ScopeInterface;

class Request implements
    \Magento\Framework\App\Action\HttpPostActionInterface,
    \Magento\Framework\App\CsrfAwareActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    private \Magento\Framework\Controller\Result\JsonFactory $jsonFactory;

    /**
     * @var \DVCampus\PersonalDiscount\Model\DiscountRequestFactory $discountRequestFactory
     */
    private \DVCampus\PersonalDiscount\Model\DiscountRequestFactory $discountRequestFactory;

    /**
     * @var \DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest $discountRequestResource
     */
    private \DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest $discountRequestResource;

    /**
     * @var \Magento\Framework\App\RequestInterface $request
     */
    private \Magento\Framework\App\RequestInterface $request;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    private \Magento\Store\Model\StoreManagerInterface $storeManager;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     */
    private \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator;

    /**
     * @var \Magento\Customer\Model\Session $customerSession
     */
    private \Magento\Customer\Model\Session $customerSession;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    private \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory;

    /**
     * @var \DVCampus\PersonalDiscount\Model\Config $config
     */
    private \DVCampus\PersonalDiscount\Model\Config $config;

    /**
     * @var \Psr\Log\LoggerInterface $logger
     */
    private \Psr\Log\LoggerInterface $logger;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     * @param \DVCampus\PersonalDiscount\Model\DiscountRequestFactory $discountRequestFactory
     * @param \DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest $discountRequestResource
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param \DVCampus\PersonalDiscount\Model\Config $config
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \DVCampus\PersonalDiscount\Model\DiscountRequestFactory $discountRequestFactory,
        \DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest $discountRequestResource,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \DVCampus\PersonalDiscount\Model\Config $config,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->jsonFactory = $jsonFactory;
        $this->discountRequestFactory = $discountRequestFactory;
        $this->discountRequestResource = $discountRequestResource;
        $this->request = $request;
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->customerSession = $customerSession;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->config = $config;
        $this->logger = $logger;
    }

    /**
     * Controller action
     *
     * @return Json
     */
    public function execute(): Json
    {
        /** @var DiscountRequest $discountRequest */
        $discountRequest = $this->discountRequestFactory->create();
        $response = $this->jsonFactory->create();

        try {
            if (!$this->config->enabled()) {
                throw new InvalidFormRequestException();
            }

            if (!$this->customerSession->isLoggedIn() && !$this->config->allowForGuests()) {
                throw new InvalidFormRequestException();
            }

            $customerId = $this->customerSession->getCustomerId()
                ? (int) $this->customerSession->getCustomerId()
                : null;

            if ($this->customerSession->isLoggedIn()) {
                $name = $this->customerSession->getCustomer()->getName();
                $email = $this->customerSession->getCustomer()->getEmail();
            } else {
                $name = $this->request->getParam('name');
                $email = $this->request->getParam('email');
            }

            $productId = (int) $this->request->getParam('product_id');
            /** @var ProductCollection $productCollection */
            $productCollection = $this->productCollectionFactory->create();
            $productCollection->addIdFilter($productId)
                ->setPageSize(1);
            $product = $productCollection->getFirstItem();
            $productId = (int) $product->getId();

            if (!$productId) {
                throw new \InvalidArgumentException("Product with id $productId does not exist");
            }

            $discountRequest->setCustomerId($customerId)
                ->setName($name)
                ->setEmail($email)
                ->setProductId($productId)
                ->setMessage($this->request->getParam('message'))
                ->setStoreId($this->storeManager->getStore()->getId());

            $this->discountRequestResource->save($discountRequest);

            if (!$this->customerSession->isLoggedIn()) {
                $this->customerSession->setDiscountRequestCustomerName($name);
                $this->customerSession->setDiscountRequestCustomerEmail($email);
                $productIds = $this->customerSession->getDiscountRequestProductIds() ?? [];
                $productIds[] = $productId;
                $this->customerSession->setDiscountRequestProductIds(array_unique($productIds));
            }

            return $response->setData([
                'message' => __(
                    'You request for product %1 accepted for review!',
                    $this->request->getParam('productName')
                )
            ]);
        } catch (\Exception $e) {
            if (!($e instanceof InvalidFormRequestException)) {
                $this->logger->error($e->getMessage());
            }
        }

        return $response->setHttpResponseCode(400);
    }

    /**
     * Create exception in case CSRF validation failed. Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
    {
        return null;
    }

    /**
     * Perform custom request validation. Return null if default validation is needed.
     *
     * @param RequestInterface $request
     * @return bool
     */
    public function validateForCsrf(RequestInterface $request): bool
    {
        return $this->formKeyValidator->validate($request);
    }
}
