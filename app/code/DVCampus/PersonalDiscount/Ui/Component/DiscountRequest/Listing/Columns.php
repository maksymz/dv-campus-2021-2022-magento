<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Ui\Component\DiscountRequest\Listing;

use DVCampus\PersonalDiscount\Model\Authorization;

class Columns extends \Magento\Ui\Component\Listing\Columns
{
    /**
     * @var \DVCampus\PersonalDiscount\Model\Authorization $authorization
     */
    private \DVCampus\PersonalDiscount\Model\Authorization $authorization;

    /**
     * @param \DVCampus\PersonalDiscount\Model\Authorization $authorization
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \DVCampus\PersonalDiscount\Model\Authorization $authorization,
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $components, $data);
        $this->authorization = $authorization;
    }

    /**
     * @inheritdoc
     */
    public function prepare(): void
    {
        parent::prepare();

        $configuration = $this->getConfiguration();

        if (!$this->authorization->isAllowed(Authorization::ACTION_DISCOUNT_REQUEST_EDIT)) {
            $configuration['editorConfig']['enabled'] = false;
        }

        $this->setData('config', $configuration);
    }
}
