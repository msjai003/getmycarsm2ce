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

namespace Gmc\Quote\Plugin\Quote\Model;

use Magento\Catalog\Model\Product\Type;
use Magento\Quote\Model\Quote as CoreQuote;
use Magento\Quote\Model\Quote\Item;
use Gmc\PatnerContribution\Model\Logger\Logger as LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;

/**
 * Class Quote
 * Gmc\Quote\Plugin\Quote\Model
 */
class Quote
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * Quote constructor.
     *
     * @param LoggerInterface $logger LoggerInterface
     * @param ResourceConnection $resourceConnection ResourceConnection
     */
    public function __construct(
        LoggerInterface $logger,
        ResourceConnection $resourceConnection
    ) {
        $this->logger = $logger;
        $this->resourceConnection = $resourceConnection;
    } //end __construct

    /**
     * @param CoreQuote $subject CoreQuote
     * @param Item $item Result
     * @return object
     */
    public function afterAddProduct(
        CoreQuote $subject,
        $item,
        $product,
        $request = null
    ) {
        try {
            if (!$item instanceof Item) {
                return $item;
            }
            if (!$request instanceof DataObject) {
                return $item;
            }
            $options = $request->getData('options');
            $priceContribution = $options['price_contribution'] ?? '';
            if (!$priceContribution) {
                return $item;
            }
            $this->setItemCustomPrice($item, $priceContribution);
        } catch (\Throwable $exception) {
            $this->logger->critical(
                $exception->getMessage(),
                [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine()
                ]
            );
        }
        return $item;
    } //end afterAddProduct()

    /**
     * @param Item $item
     * @param Float $priceContribution
     * @return Item
     */
    private function setItemCustomPrice(Item $item, $priceContribution)
    {
        $productType = $item->getProductType();
        if ($productType != Type::TYPE_VIRTUAL) {
            return;
        }
        $item->setCustomPrice($priceContribution);
        $item->setOriginalCustomPrice($priceContribution);
        $item->getProduct()->setIsSuperMode(true);
    } //end setItemCustomPrice()
}//end class
