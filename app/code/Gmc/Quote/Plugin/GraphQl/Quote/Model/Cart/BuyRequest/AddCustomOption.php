<?php

declare(strict_types=1);
/**
 * Gmc_Quote
 *
 * PHP version 8.x
 *
 * @category  PHP
 * @package   Gmc\Quote
 * @author    Gmc
 * @copyright 2023 Copyright Gmc
 */

namespace Gmc\Quote\Plugin\GraphQl\Quote\Model\Cart\BuyRequest;

use Magento\Quote\Model\Cart\BuyRequest\CustomizableOptionDataProvider;

class AddCustomOption
{

    /**
     * @param CustomizableOptionDataProvider $subject
     * @param $result
     * @param CartItem $cartItem
     * @return mixed
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterExecute(
        CustomizableOptionDataProvider $subject,
        $result,
        $cartItem
    ) {
        $option = $result['options'] ?? [];
        $priceContribution = $cartItem->getPriceContribution();
        $option['price_contribution'] = $priceContribution;
        $result['options'] = $option;

        return $result;
    } //end afterExecute()
}//end class
