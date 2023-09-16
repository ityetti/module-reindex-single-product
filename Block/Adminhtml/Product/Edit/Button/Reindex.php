<?php

declare(strict_types=1);

namespace ITYetti\ReindexSingleProduct\Block\Adminhtml\Product\Edit\Button;

use ITYetti\ReindexSingleProduct\Service\Config;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class Reindex implements ButtonProviderInterface
{
    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var Context
     */
    private Context $context;

    /**
     * @param Config $config
     * @param Context $context
     */
    public function __construct(
        Config $config,
        Context $context
    ) {
        $this->config = $config;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getButtonData(): array
    {
        if ($this->config->isEnabled()) {
            $message = __('Are you sure you want to reindex product data?');
            return [
                'label' => __('Reindex Product Data'),
                'class' => 'action-secondary',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => 'product_form.product_form.product_reindex',
                                    'actionName' => 'reindex',
                                    'params' => [
                                        false
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                'on_click' => "confirmSetLocation('{$message}', '{$this->getButtonUrl()}')",
                'sort_order' => 30
            ];
        }
        return [];
    }

    /**
     * Get reindex url
     *
     * @return string
     */
    private function getButtonUrl(): string
    {
        return $this->getUrl('ityetti/product/reindex', ['id' => $this->context->getRequestParam('id')]);
    }

    /**
     * Generate url by route and parameters
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    private function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrl($route, $params);
    }
}
