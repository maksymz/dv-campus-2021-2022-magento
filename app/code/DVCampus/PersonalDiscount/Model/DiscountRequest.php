<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Model;

/**
 * @method int|string|null getDiscountRequestId()
 * @method int|string|null getProductId()
 * @method $this setProductId(int $productId)
 * @method int|string|null getCustomerId()
 * @method $this setCustomerId(int $customerId)
 * @method string|null getName()
 * @method $this setName(string $name)
 * @method string|null getEmail()
 * @method $this setEmail(string $name)
 * @method string|null getMessage()
 * @method $this setMessage(string $message)
 * @method int|string getStatus()
 * @method $this setStatus(int $status)
 * @method int|string|null getStoreId()
 * @method $this setStoreId(int $websiteId)
 * @method int|string|null getCreatedAt()
 * @method int|string|null getUpdatedAt()
 */
class DiscountRequest extends \Magento\Framework\Model\AbstractModel
{
    public const STATUS_PENDING = 1;
    public const STATUS_APPROVED = 2;
    public const STATUS_DECLINED = 3;

    /**
     * @inheritDoc
     */
    protected function _construct(): void
    {
        parent::_construct();
        $this->_init(\DVCampus\PersonalDiscount\Model\ResourceModel\DiscountRequest::class);
    }
}
