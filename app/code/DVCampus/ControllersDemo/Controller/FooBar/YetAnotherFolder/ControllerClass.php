<?php

declare(strict_types=1);

namespace DVCampus\ControllersDemo\Controller\FooBar\YetAnotherFolder;

use Magento\Framework\Controller\Result\Json;

class ControllerClass implements
    \Magento\Framework\App\Action\HttpGetActionInterface
{
    private \Magento\Framework\App\RequestInterface $request;

    private \Magento\Framework\Controller\Result\JsonFactory $jsonFactory;

    /**
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory
    ) {
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * Controller demo
     * https://dv-campus-2021-2022-magento.local/dv-campus-controller-demo/foobar_yetanotherfolder/controllerclass/parameter-name-1/test/
     *
     * @return Json
     */
    public function execute(): Json
    {
        return $this->jsonFactory->create()
            ->setData([
                'parameter-name-1' => $this->request->getParam('parameter-name-1', ''),
                'demo_int' => (int) $this->request->getParam('demo-int', 10)
            ]);
    }
}
