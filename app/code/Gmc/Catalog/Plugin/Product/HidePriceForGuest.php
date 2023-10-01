<?php

declare(strict_types=1);
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

namespace Gmc\Catalog\Plugin\Product;

use Magento\Framework\Pricing\Render as PricingRender;
use Gmc\PartnerContribution\Model\Logger\Logger;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class HidePriceForGuest
 * Gmc\Catalog\Plugin\Model\Product
 */
class HidePriceForGuest
{
    /**
     * SetPartnerPrice constructor.
     *
     * @param Logger $logger Logger
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private Logger $logger,
        private CustomerSession $customerSession
    ) {
    } //end __construct()

    /**
     * @param PricingRender $subject
     * @param mixed $result Result
     * @return string
     */
    public function afterRender(
        PricingRender $subject,
        $result,
        $priceCode,
        $saleableItem,
        $arguments
    ) {
        try {
            $isCustomerLoggedIn = $this->customerSession->isLoggedIn();
            if ($isCustomerLoggedIn) {
                return $result;
            }
            $priceTypeCode = $arguments['price_type_code'] ?? '';
            if ($priceTypeCode == 'tier_price') {
                return '';
            }
            return '<p><a href="javascript:void(0)" class="open-popup-login">Login to see Price!</a></p>';
        } catch (\Throwable $exception) {
            $this->logger->critical(
                'Error occurred while hide product price for Guest!',
                [
                    'error' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine()
                ]
            );
        }
        return $result;
    } //end afterRender()   
}//end class
