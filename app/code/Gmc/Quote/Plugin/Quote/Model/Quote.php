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
use Gmc\PartnerContribution\Model\Logger\Logger as LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Catalog\Model\Product;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Customer\Model\Session as CustomerSession;

/**
 * Class Quote
 * Gmc\Quote\Plugin\Quote\Model
 */
class Quote
{
    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * Quote constructor.
     *
     * @param LoggerInterface $logger
     * @param ResourceConnection $resourceConnection
     * @param CustomerSession $customerSession
     */
    public function __construct(
        private LoggerInterface $logger,
        private ResourceConnection $resourceConnection,
        private CustomerSession $customerSession
    ) {
        $this->connection = $resourceConnection->getConnection();
    } //end __construct

    /**
     * @param CoreQuote    $subject     CoreQuote
     * @param Product      $product     Product
     * @param null         $request     Request Params
     * @param string       $processMode processModeObject
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeAddProduct(
        CoreQuote   $subject,
        Product     $product,
        $request = null
    ) {
        $isCustomerLoggedIn = $this->customerSession->isLoggedIn();
        if (!$isCustomerLoggedIn) {
            throw new LocalizedException(__('Booking not allowed for Guest customer!'));
        }
        if (!$request instanceof DataObject) {
            return;
        }
        $productTicketSize = (int) $product->getTicketSize();
        if (!$productTicketSize) {
            return;
        }
        $productId = $product->getId();
        $select = $this->connection->select();
        $select
        ->from('gmc_partner_contribution', ['SUM(ticket_size_qty) AS ticket_size_ordered'])
        ->where('product_id = ?', $productId)
        ->group('product_id');

        $ticketSizeOrdered = (int) $this->connection->fetchOne($select);
        if ($ticketSizeOrdered >= $productTicketSize) {
            throw new LocalizedException(__('Booking no longer available fro the product!'));
        }
    }//end beforeAddProduct()    

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
            $ticketSize = (int) $product->getTicketSize();
            $ticketFee = (int) $product->getTicketFee();
            if (!$ticketSize || !$ticketFee) {
                return $item;
            }
            $this->setItemCustomPrice($item, $ticketFee);
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
     * @param Float $customPrice
     * @return Item
     */
    private function setItemCustomPrice(Item $item, $customPrice)
    {
        $productType = $item->getProductType();
        if ($productType != Type::TYPE_SIMPLE) {
            return;
        }
        $item->setCustomPrice($customPrice);
        $item->setOriginalCustomPrice($customPrice);
        $item->getProduct()->setIsSuperMode(true);
    } //end setItemCustomPrice()
}//end class
