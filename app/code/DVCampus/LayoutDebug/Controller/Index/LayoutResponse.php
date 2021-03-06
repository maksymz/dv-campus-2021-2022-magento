<?php

declare(strict_types=1);

namespace DVCampus\LayoutDebug\Controller\Index;

use Magento\Framework\View\Result\Layout;

class LayoutResponse implements \Magento\Framework\App\Action\HttpGetActionInterface
{
    /**
     * @var \Magento\Framework\View\Result\LayoutFactory $layoutFactory
     */
    private \Magento\Framework\View\Result\LayoutFactory $layoutFactory;

    /**
     * @param \Magento\Framework\View\Result\LayoutFactory $layoutFactory
     */
    public function __construct(\Magento\Framework\View\Result\LayoutFactory $layoutFactory)
    {
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Layout result demo: https://dv-campus-2021-2022-magento.local/dv-campus-layout-debug/index/layoutresponse
     *
     * @return Layout
     */
    public function execute(): Layout
    {
        return $this->layoutFactory->create();
    }
}
