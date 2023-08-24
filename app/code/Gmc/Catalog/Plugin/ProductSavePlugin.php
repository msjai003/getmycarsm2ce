<?php

namespace Gmc\Catalog\Plugin;

use Magento\Catalog\Model\Product;

class ProductSavePlugin
{
    public function beforeSave(
        Product $product
    ) {

        $procurementFee = $product->getData('procurement_fee');
        $rcFee = $product->getData('rc_fee');
        $sdcFee = $product->getData('service_dry_cleaning_fee');
        $shippingFee = $product->getData('shipping_fee');
        $margin = $product->getData('margin');
        $ticketSize = $product->getData('ticket_size');
        $ticketFee = $product->getData('ticket_fee');

        // Calculate Final Partner Price
        $partnerPrice = $procurementFee + $rcFee + $sdcFee + $shippingFee;
        $product->setPrice($partnerPrice);

        // Perform your price calculation based on custom attributes
        $estSellingPrice = $this->calculateEstimatedSellingPrice($partnerPrice, $margin);
        $product->setEstimatedSellingPrice($estSellingPrice);

        // Calculate Ticket Size value
        $ticketFee = $partnerPrice / $ticketSize;
        $product->setTicketFee($ticketFee);

        return [$product];
    }

    /**
     * @param $partnerPrice
     * @param $margin
     * @return float|int
     */
    protected function calculateEstimatedSellingPrice($partnerPrice, $margin)
    {
        $estSellingPrice = $partnerPrice + ($partnerPrice * ($margin / 100));
        return $estSellingPrice;
    }
}
