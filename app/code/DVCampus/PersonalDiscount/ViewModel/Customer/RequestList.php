<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\ViewModel\Customer;

use DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest\Collection as DiscountRequestCollection;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Catalog\Model\Product;

class RequestList implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var \DVCampus\PersonalDiscount\Model\CustomerRequestsProvider $customerRequestsProvider
     */
    private \DVCampus\PersonalDiscount\Model\CustomerRequestsProvider $customerRequestsProvider;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    private \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility $productVisibility
     */
    private \Magento\Catalog\Model\Product\Visibility $productVisibility;

    /**
     * @var ProductCollection $loadedProductCollection
     */
    private ProductCollection $loadedProductCollection;

    /**
     * @param \DVCampus\PersonalDiscount\Model\CustomerRequestsProvider $customerRequestsProvider
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param Product\Visibility $productVisibility
     */
    public function __construct(
        \DVCampus\PersonalDiscount\Model\CustomerRequestsProvider $customerRequestsProvider,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility
    ) {
        $this->customerRequestsProvider = $customerRequestsProvider;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productVisibility = $productVisibility;
    }

    /**
     * Get a list of customer discount requests
     *
     * @return DiscountRequestCollection
     */
    public function getDiscountRequestCollection(): DiscountRequestCollection
    {
        return $this->customerRequestsProvider->getCurrentCustomerRequestCollection();
    }

    /**
     * Get product for customer discount request
     *
     * @param int $productId
     * @return Product|null
     */
    public function getProduct(int $productId): ?Product
    {
        if (isset($this->loadedProductCollection)) {
            return $this->loadedProductCollection->getItemById($productId);
        }

        $discountRequestCollection = $this->getDiscountRequestCollection();
        $productIds = array_unique(array_filter($discountRequestCollection->getColumnValues('product_id')));

        $productCollection = $this->productCollectionFactory->create();
        // Inactive products are filtered by default
        $productCollection->addAttributeToFilter('entity_id', ['in' => $productIds])
            ->addAttributeToSelect('name')
            ->addWebsiteFilter()
            ->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $this->loadedProductCollection = $productCollection;

        return $this->loadedProductCollection->getItemById($productId);
    }
}
