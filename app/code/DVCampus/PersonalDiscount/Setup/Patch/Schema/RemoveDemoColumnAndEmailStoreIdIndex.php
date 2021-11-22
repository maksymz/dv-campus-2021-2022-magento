<?php

declare(strict_types=1);

namespace DVCampus\PersonalDiscount\Setup\Patch\Schema;

class RemoveDemoColumnAndEmailStoreIdIndex implements \Magento\Framework\Setup\Patch\SchemaPatchInterface
{
    /**
     * @var \Magento\Framework\Setup\SchemaSetupInterface $schemaSetup
     */
    private \Magento\Framework\Setup\SchemaSetupInterface $schemaSetup;

    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $schemaSetup
     */
    public function __construct(
        \Magento\Framework\Setup\SchemaSetupInterface $schemaSetup
    ) {
        $this->schemaSetup = $schemaSetup;
    }

    /**
     * Run code inside patch
     *
     * @return RemoveDemoColumnAndEmailStoreIdIndex
     */
    public function apply(): self
    {
        $connection = $this->schemaSetup->getConnection();
        $tableName = $this->schemaSetup->getTable('dv_campus_personal_discount_request');

        $connection->dropColumn($tableName, 'demo_column_to_be_deleted');

        foreach ($connection->getIndexList($tableName) as $indexName => $indexMetadata) {
            if ($indexMetadata['INDEX_TYPE'] === 'unique'
                && count($indexMetadata['COLUMNS_LIST']) === 2
                && !array_diff($indexMetadata['COLUMNS_LIST'], ['email', 'store_id'])
            ) {
                $connection->dropIndex($tableName, $indexName);
                break;
            }
        }

        return $this;
    }

    /**
     * Get patch dependencies
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Get patch aliases
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
