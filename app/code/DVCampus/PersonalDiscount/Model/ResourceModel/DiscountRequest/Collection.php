<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(
            \DVCampus\PersonalDiscount\Model\DiscountRequest::class,
            \DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest::class
        );
    }
}
