<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Model;

class CurrentProductIdUpdater implements \Magento\Framework\View\Layout\Argument\UpdaterInterface
{
    /**
     * @var \Magento\Catalog\Helper\Data $productHelper
     */
    private \Magento\Catalog\Helper\Data $productHelper;

    /**
     * @var \DVCampus\PersonalDiscount\Model\Config $config
     */
    private \DVCampus\PersonalDiscount\Model\Config $config;

    /**
     * @param \Magento\Catalog\Helper\Data $productHelper
     * @param Config $config
     */
    public function __construct(
        \Magento\Catalog\Helper\Data $productHelper,
        \DVCampus\PersonalDiscount\Model\Config $config
    ) {
        $this->productHelper = $productHelper;
        $this->config = $config;
    }

    /**
     * Set current product id to jsLayout for passing it to the Knockout component
     *
     * @param array $value
     * @return array
     */
    public function update($value): array
    {
        // Product is not present when Varnish ESI block are rendered via \Magento\PageCache\Controller\Block\Esi
        if ($this->productHelper->getProduct()) {
            $value['components']['personalDiscountRequest']['children']['personalDiscountRequestForm']['config']
                ['productId'] = (int) $this->productHelper->getProduct()->getId();
            $value['components']['personalDiscountRequest']['children']['personalDiscountRequestLoginButton']['config']
                ['allowForGuests'] = (bool) $this->config->allowForGuests();
        }

        return $value;
    }
}
