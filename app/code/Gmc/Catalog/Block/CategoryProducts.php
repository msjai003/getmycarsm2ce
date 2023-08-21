<?php

namespace Gmc\Catalog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class CategoryProducts extends Template
{
    protected $_categoryFactory;

    protected $productRepository;

    protected $priceCurrency;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        ProductRepositoryInterface $productRepository,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryFactory = $categoryFactory;
        $this->productRepository = $productRepository;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * @param $categoryId
     * @return \Magento\Framework\Data\Collection\AbstractDb
     */
    public function getCategoryProducts($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category->getProductCollection();
    }

    public function getProductById($productId)
    {
        try {
            return $this->productRepository->getById($productId);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            return null;
        }
    }

    public function getImageUrl($product, $imageType)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . 'catalog/product' . $product->getImage();
    }

    public function getFormattedPriceWithCurrency($price)
    {
        return $this->priceCurrency->format($price, false, PriceCurrencyInterface::DEFAULT_PRECISION, $this->_storeManager->getStore());
    }
}
