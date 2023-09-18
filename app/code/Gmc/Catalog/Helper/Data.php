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

declare(strict_types=1);

namespace Gmc\Catalog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Store\Api\Data\StoreInterface;


class Data extends AbstractHelper
{
    // Constants
    const XML_PATH_PRICE_CONTRIBUTION_STEP = 'partner_settings/general/contribution_price_step';

    const   XML_HOME_PAGE_PRODUCTS_TAB_1 = 'home_settings/general/tab_1';

    const XML_HOME_PAGE_PRODUCTS_TAB_2 = 'home_settings/general/tab_2';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;

    /**
     * @var PricingHelper
     */
    private $pricingHelper;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    private $connection;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var StoreInterface
     */
    private $storeManager;

    /**
     * Data constructor.
     * @param Context $context Context
     * @param ResourceConnection $resource
     * @param ProductRepositoryInterface $productRepository
     * @param StoreInterface $storeManager
     */
    public function __construct(
        Context $context,
        PricingHelper $pricingHelper,
        ResourceConnection $resource,
        ProductRepositoryInterface $productRepository,
        StoreInterface $storeManager
    ) {
        $this->scopeConfig = $context->getScopeConfig();
        $this->pricingHelper = $pricingHelper;
        $this->connection = $resource->getConnection();
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    } //end __construct()

    /**
     * Function to return price contribution range
     *
     * @param mixed $product
     * @return mixed
     */
    public function getAllowedContributionRange($product)
    {
        if (!$product instanceof ProductInterface) {
            $product = $this->productRepository->getById($product);
        }
        $productPrice = $product->getPrice();
        $availableContribution = $this->getPriceContribution($product->getId());
        if (!empty($availableContribution) && ($availableContribution['partner_price'] <= 0)) {
            return [];
        }
        $price = empty($availableContribution) ? $productPrice : $availableContribution['partner_price'];
        $step = (int) $this->scopeConfig->getValue(self::XML_PATH_PRICE_CONTRIBUTION_STEP);
        if ($price <= $step) {
            $formattedPrice = $this->getFormattedPrice($price);
            return [base64_encode($price) => $formattedPrice];
        }
        $range = floor($price / $step);
        $priceContributionRange = [];
        for ($inc = 1; $inc <= $range; $inc++) {
            $price = $step * $inc;
            $priceContributionRange[base64_encode((string) $price)] = $this->getFormattedPrice($price);
        }
        return $priceContributionRange;
    } //end getAllowedContributionRange()

    public function getCurrencySymbol()
    {
        return $this->storeManager->getCurrentCurrency()->getCurrencySymbol();
    }

    /**
     * Function to retrieve formatted price
     */
    private function getFormattedPrice($price)
    {
        return $this->pricingHelper->currency($price, true, false);
    } //end getFormattedPrice()

    /**
     * Function to retrieve price contribution details by product ID
     *
     * @param mixed $product
     * @return array
     */
    public function getPriceContribution($product)
    {
        if (!$product instanceof ProductInterface) {
            $product = $this->productRepository->getById($product);
        }
        $productId = $product->getId();
        $connection = $this->connection;

        $table = $connection->getTableName('gmc_partner_contribution');
        $select = $connection->select();
        $select
        ->from($table, ['SUM(ticket_size_qty) AS ticket_size_ordered'])
        ->where('product_id = ?', $productId)
        ->group('product_id');

        $ticketSizeOrdered = (int) $connection->fetchOne($select);
        $price = $product->getPrice();
        $productTicketSize = $product->getTicketSize();
        if (empty($ticketSizeOrdered)) {
            return [
                'contribution_booked' => 0
            ];
        }
        if ($ticketSizeOrdered >= $productTicketSize) {
            return [
                'contribution_booked' => 100
            ];
        }
        $contributionBooked = $this->getContributionBooked($productId);
        $result['contribution_booked'] = (int) (($contributionBooked / $price) * 100);
        return $result;
    } //end getPriceContribution()

    /**
     * Function to retrieve total contribution booked by product ID
     *
     * @return int
     */
    public function getContributionBooked($productId)
    {
        $connection = $this->connection;
        $table = $connection->getTableName('gmc_partner_contribution');
        $select = $connection->select();
        $select
            ->from($table, ['SUM(amount_contributed)'])
            ->where('product_id = ?', $productId)
            ->group('product_id');
        return (int) $connection->fetchOne($select);
    } //end getContributionBooked()

    public function getTabOneId()
    {
        return (int) $this->scopeConfig->getValue(self::XML_HOME_PAGE_PRODUCTS_TAB_1);
    }

    public function getTabTwoId()
    {
        return (int) $this->scopeConfig->getValue(self::XML_HOME_PAGE_PRODUCTS_TAB_2);
    }
}//end class
