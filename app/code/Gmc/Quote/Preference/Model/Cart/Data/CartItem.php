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

namespace Gmc\Quote\Preference\Model\Cart\Data;

use Magento\Quote\Model\Cart\Data\CartItem as QuoteCartItem;

/**
 * Represents cart item data
 */
class CartItem extends QuoteCartItem
{

    /**
     * @var float
     */
    private $priceContribution;


    /**
     * @param string $sku
     * @param float $quantity
     * @param string|null $parentSku
     * @param array|null $selectedOptions
     * @param array|null $enteredOptions
     * @param float|null $priceContribution
     */
    public function __construct(
        string $sku,
        float $quantity,
        string $parentSku = null,
        array $selectedOptions = null,
        array $enteredOptions = null,
        float $priceContribution = null
    ) {
        parent::__construct(
            $sku,
            $quantity,
            $parentSku,
            $selectedOptions,
            $enteredOptions
        );
        $this->priceContribution = $priceContribution;
    } //end __construct()    

    /**
     * Returns Price Contribution
     *
     * @return mixed
     */
    public function getPriceContribution()
    {
        return $this->priceContribution;
    } //end getPriceContribution()
}//end class
