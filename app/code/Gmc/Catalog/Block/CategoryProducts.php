<?php

namespace Gmc\Catalog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\UrlInterface;
use Gmc\Catalog\Helper\Data;
use Magento\Customer\Model\Session as CustomerSession;

class CategoryProducts extends Template
{
    protected $_categoryFactory;

    protected $productRepository;

    protected $priceCurrency;
    protected $urlInterface;

    protected $helper;

    private $customerSession;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        PriceCurrencyInterface $priceCurrency,
        UrlInterface $urlInterface,
        Data $helper,
        CustomerSession $customerSession,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryFactory = $categoryFactory;
        $this->priceCurrency = $priceCurrency;
        $this->urlInterface = $urlInterface;
        $this->helper = $helper;
        $this->customerSession = $customerSession;
    }

    /**
     * @param $categoryId
     * @return string
     */
    public function getCategoryProducts($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        $productCollection = $category->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED);

        return $productCollection;
    }

    /**
     * @param $product
     * @param $imageType
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getImageUrl($product, $imageType)
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)
            . 'catalog/product' . $product->getImage();
    }

    /**
     * @param $price
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFormattedPriceWithCurrency($price)
    {
        return $this->priceCurrency->format($price, false, PriceCurrencyInterface::DEFAULT_PRECISION, $this->_storeManager->getStore());
    }

    /**
     * @param $product
     * @return string
     */
    public function getProductUrl($product)
    {
        return $this->urlInterface->getUrl('catalog/product/view', ['id' => $product->getId()]);
    }

    public function getTabOneId()
    {
        return $this->helper->getTabOneId();
    }

    public function getTabTwoId()
    {
        return $this->helper->getTabTwoId();
    }

    public function getListAttributesValue($product, $codes)
    {
        $arr = [];
        foreach ($codes as $code) {
            $attribute = $product->getResource()->getAttribute($code);
            if ($attribute) {
                $arr[] = [
                    'label' => $attribute->getStoreLabel(),
                    'value' => $attribute->getFrontend()->getValue($product)
                ];
            }
        }
        return $arr;
    }

    public function getAttributeValue($product, $code)
    {
        $val = '';
        $attribute = $product->getResource()->getAttribute($code);
        if ($attribute) {
            $val = $attribute->getFrontend()->getValue($product);
        }
        return $val;
    }

    /**
     * Function to check is customer logged in
     */
    public function isCustomerLoggedIn()
    {
        return $this->customerSession->isLoggedIn();
    } //end isCustomerLoggedIn()     
}
