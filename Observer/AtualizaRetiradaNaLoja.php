<?php

namespace Espaker\RetiradaNaLoja\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\ResourceConnection;

class AtualizaRetiradaNaLoja implements ObserverInterface
{
    protected $resource;

    public function __construct(ResourceConnection $resource)
    {
        $this->resource = $resource;
    }

    public function execute(Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (!$order || !$order->getId()) {
            return;
        }

        $isRetirada = ($order->getShippingMethod() === 'retiradaloja_retiradaloja');

        $connection = $this->resource->getConnection();
        $salesOrderGrid = $this->resource->getTableName('sales_order_grid');

        $connection->update(
            $salesOrderGrid,
            ['retirada_na_loja' => (int) $isRetirada],
            ['entity_id = ?' => $order->getId()]
        );
    }
}
