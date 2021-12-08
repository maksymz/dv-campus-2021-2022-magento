<?php

declare(strict_types=1);

namespace DVCampus\LayoutDebug\Observer;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Layout;

class DumpMergedLayoutAndHandles implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\UrlInterface $url
     */
    private \Magento\Framework\UrlInterface $url;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList $dir
     */
    private \Magento\Framework\App\Filesystem\DirectoryList $dir;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File $fileDriver
     */
    private \Magento\Framework\Filesystem\Driver\File $fileDriver;

    /**
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\Filesystem\DirectoryList $dir
     * @param \Magento\Framework\Filesystem\Driver\File $fileDriver
     */
    public function __construct(
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Filesystem\DirectoryList $dir,
        \Magento\Framework\Filesystem\Driver\File $fileDriver
    ) {
        $this->url = $url;
        $this->dir = $dir;
        $this->fileDriver = $fileDriver;
    }

    /**
     * Dump page layout handles and current page layout
     *
     * @param Observer $observer
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute(Observer $observer): void
    {
        /** @var Layout $layout */
        $layout = $observer->getEvent()->getData('layout');
        $logsDir = $this->dir->getPath(DirectoryList::LOG) . DIRECTORY_SEPARATOR;
        // Get page layout handles
        $layoutHandles = ['Current page URL: ' . $this->url->getCurrentUrl()];

        foreach ($layout->getUpdate()->getHandles() as $handle) {
            $layoutHandles[] = '- ' . $handle;
        }

        $this->fileDriver->filePutContents(
            $logsDir . 'layout_handles.log',
            implode("\n", $layoutHandles) . "\n\n",
            FILE_APPEND
        );

        // Get merged page layout
        $this->fileDriver->filePutContents(
            $logsDir . 'layout_merged.xml',
            $layout->getXmlString() . "\n\n"
        );
    }
}
