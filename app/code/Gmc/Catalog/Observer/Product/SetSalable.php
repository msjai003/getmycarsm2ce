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

namespace Gmc\Catalog\Observer\Product;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Gmc\PartnerContribution\Model\Logger\Logger;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class SetSalable
 *
 * Gmc\Catalog\Observer\Product
 */
class SetSalable implements ObserverInterface
{
    /**
     * AddPartnerPrice Constructor.
     *
     * @param Logger $logger Logger
     * @param ResourceConnection $resource
     */
    public function __construct(
        private Logger $logger,
        private CustomerSession $customerSession
    ) {
    } //end __construct()

    /**
     * @param Observer $observer
     * @return mixed|void
     */
    public function execute(Observer $observer)
    {
        try {
            $salableObject = $observer->getEvent()->getSalable();
            $isCustomerLoggedIn = $this->customerSession->isLoggedIn();
            if (!$isCustomerLoggedIn) {
                $salableObject->setIsSalable(0);
            }
        } catch (\Exception $exception) {
            $message = "Error occurred while set salable flag";
            $context = [
                'error' => $exception->getMessage(),
                'class' => __CLASS__
            ];
            $this->logger->critical($message, $context);
        }
    } //end execute()   
}//end class
