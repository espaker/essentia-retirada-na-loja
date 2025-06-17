<?php
namespace Espaker\RetiradaNaLoja\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class AddRetiradaNaLojaToSalesOrderGrid implements DataPatchInterface
{
    private $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public function apply()
    {
        $this->moduleDataSetup->startSetup();

        $this->moduleDataSetup->getConnection()->addColumn(
            $this->moduleDataSetup->getTable('sales_order_grid'),
            'retirada_na_loja',
            [
                'type' => Table::TYPE_BOOLEAN,
                'nullable' => false,
                'default' => 0,
                'comment' => 'Retirada na Loja',
            ]
        );

        $this->moduleDataSetup->endSetup();
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
