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

use Magento\Quote\Model\Cart\Data\CartItemFactory as QuoteCartItemFactory;
use Magento\Quote\Model\Cart\Data\EnteredOption;
use Magento\Quote\Model\Cart\Data\SelectedOption;
use Magento\Framework\Exception\InputException;

/**
 * Represents cart item data
 */
class CartItemFactory extends QuoteCartItemFactory
{
    /**
     * Creates CartItem DTO
     *
     * @param array $data
     * @return CartItem
     * @throws InputException
     */
    public function create(array $data): CartItem
    {
        if (!isset($data['sku'], $data['quantity'])) {
            throw new InputException(__('Required fields are not present: sku, quantity'));
        }
        return new CartItem(
            $data['sku'],
            $data['quantity'],
            $data['parent_sku'] ?? null,
            isset($data['selected_options']) ? $this->createSelectedOptions($data['selected_options']) : [],
            isset($data['entered_options']) ? $this->createEnteredOptions($data['entered_options']) : [],
            $data['price_contribution'] ?? ''
        );
    }

    /**
     * Creates array of Entered Options
     *
     * @param array $options
     * @return EnteredOption[]
     */
    private function createEnteredOptions(array $options): array
    {
        return \array_map(
            function (array $option) {
                if (!isset($option['uid'], $option['value'])) {
                    throw new InputException(
                        __('Required fields are not present EnteredOption.uid, EnteredOption.value')
                    );
                }
                return new EnteredOption($option['uid'], $option['value']);
            },
            $options
        );
    }

    /**
     * Creates array of Selected Options
     *
     * @param string[] $options
     * @return SelectedOption[]
     */
    private function createSelectedOptions(array $options): array
    {
        return \array_map(
            function ($option) {
                return new SelectedOption($option);
            },
            $options
        );
    }
}//end class
