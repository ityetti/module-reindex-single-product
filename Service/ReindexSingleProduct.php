<?php

declare(strict_types=1);

namespace ITYetti\ReindexSingleProduct\Service;

use Exception;
use Magento\Framework\Indexer\IndexerRegistry;

class ReindexSingleProduct
{
    private const INDEX_LIST = [
        'catalog_category_product',
        'catalog_product_category',
        'catalog_product_attribute',
        'cataloginventory_stock',
        'inventory',
        'catalogsearch_fulltext',
        'catalog_product_price',
        'catalogrule_product',
        'catalogrule_rule'
    ];

    /**
     * @var IndexerRegistry
     */
    private IndexerRegistry $indexerRegistry;

    /**
     * @param IndexerRegistry $indexerRegistry
     */
    public function __construct(
        IndexerRegistry $indexerRegistry,
    ) {
        $this->indexerRegistry = $indexerRegistry;
    }

    /**
     * Reindex data for single product
     *
     * @param int $productId
     * @return void
     * @throws Exception
     */
    public function execute(int $productId): void
    {
        foreach (self::INDEX_LIST as $index) {
            $this->indexerRegistry->get($index)->reindexRow($productId);
        }
    }
}
