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

namespace Gmc\Catalog\Model\Resolver\Product;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class ContributionPriceRange implements ResolverInterface
{
    /**
     * Constants
     */
    const XML_PATH_CONTRIBUTION_PRICE_RANGE_STEP = 'gmc/general/contribution_price_step';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ContributionPriceRange constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ProductRepositoryInterface $productRepository
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->productRepository = $productRepository;
    } //end __construct()

    /**
     * @param Field                                                      $field   Field
     * @param \Magento\Framework\GraphQl\Query\Resolver\ContextInterface $context ContextInterface
     * @param ResolveInfo                                                $info    ResolveInfo
     * @param array|null                                                 $value   Value
     * @param array|null                                                 $args    Args
     * @return \Magento\Framework\GraphQl\Query\Resolver\Value|mixed
     * @throws \Exception
     * phpcs:disable Magento2.Exceptions.DirectThrow.FoundDirectThrow
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $product = $value['model'];
        /** @var Product $product */
        $product = $this->productRepository->get($product->getSku());
        $priceRangeStep = $this->scopeConfig->getValue(self::XML_PATH_CONTRIBUTION_PRICE_RANGE_STEP);
        return [
            'min_price' => $priceRangeStep,
            'max_price' => $product->getPrice(),
            'price_range_step' => $priceRangeStep,
        ];
    } //end resolve()
}//end class
