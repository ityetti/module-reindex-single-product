<?php

declare(strict_types=1);

namespace ITYetti\ReindexSingleProduct\Controller\Adminhtml\Product;

use Exception;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Message\ManagerInterface;
use ITYetti\ReindexSingleProduct\Service\ReindexSingleProduct;
use Psr\Log\LoggerInterface;

class Reindex implements ActionInterface
{
    /**
     * @var Http
     */
    private Http $request;

    /**
     * @var RedirectFactory
     */
    private RedirectFactory $redirectFactory;

    /**
     * @var ManagerInterface
     */
    private ManagerInterface $messageManager;

    /**
     * @var ReindexSingleProduct
     */
    private ReindexSingleProduct $reindexSingleProduct;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param Http $request
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     * @param ReindexSingleProduct $reindexSingleProduct
     * @param LoggerInterface $logger
     */
    public function __construct(
        Http $request,
        RedirectFactory $redirectFactory,
        ManagerInterface $messageManager,
        ReindexSingleProduct $reindexSingleProduct,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->redirectFactory = $redirectFactory;
        $this->messageManager = $messageManager;
        $this->reindexSingleProduct = $reindexSingleProduct;
        $this->logger = $logger;
    }

    /**
     * Run reindex for single product
     *
     * @return Redirect
     * @throws Exception
     */
    public function execute(): Redirect
    {
        $resultRedirect = $this->redirectFactory->create();
        $productId = $this->request->getParam('id');
        try {
            $this->reindexSingleProduct->execute((int)$productId);
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__('Reindex ended unsuccessfully. See logs for details.'));
            $this->logger->critical($e->getMessage());
        }
        $this->messageManager->addSuccessMessage(__('You successfully reindex data'));

        return $resultRedirect->setPath('catalog/*/edit', ['id' => $productId]);
    }
}
