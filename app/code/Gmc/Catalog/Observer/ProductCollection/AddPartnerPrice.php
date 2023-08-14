<?php

/**
 * Gmc_Catalog
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Catalog
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

namespace Gmc\Catalog\Observer\ProductCollection;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface as Logger;

/**
 * Class AddPartnerPrice
 *
 * Gmc\Catalog\Observer\ProductCollection
 */
class AddPartnerPrice implements ObserverInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * AddPartnerPrice Constructor.
     *
     * @param Logger $logger Logger
     */
    public function __construct(
        Logger $logger
    ) {
        $this->logger = $logger;
    } //end __construct()

    /**
     * @param Observer $observer
     * @return mixed|void
     */
    public function execute(Observer $observer)
    {
        try {
            /**
             * @var $collection \Magento\Catalog\Model\ResourceModel\Product\Collection
             */
            $collection = $observer->getEvent()->getCollection();
            $collection
                ->getSelect()
                ->joinLeft(
                    ['partner' => $collection->getTable('gmc_product_partner_price_range')],
                    'e.entity_id = partner.product_id',
                    [
                        'partner_price'
                    ]
                );
        } catch (\Exception $exception) {
            $message = "Error occurred while set product partner price";
            $context = [
                'error' => $exception->getMessage(),
                'class' => __CLASS__
            ];
            $this->logger->critical($message, $context);
        }
    } //end execute()
}//end class
