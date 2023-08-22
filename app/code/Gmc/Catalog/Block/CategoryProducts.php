<?php

namespace Gmc\Catalog\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\UrlInterface;

class CategoryProducts extends Template
{
    protected $_categoryFactory;

    protected $productRepository;

    protected $priceCurrency;
    protected $urlInterface;

    public function __construct(
        Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        PriceCurrencyInterface $priceCurrency,
        UrlInterface $urlInterface,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_categoryFactory = $categoryFactory;
        $this->priceCurrency = $priceCurrency;
        $this->urlInterface = $urlInterface;
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

        // Create and return HTML for displaying products in the tab
        $html = '<div class="row">';
        foreach ($productCollection as $product) {
            $contributionBooked = $product->getContributionBooked();
            $contributionBooked = ($contributionBooked === null) ? 0 : $contributionBooked;
            $html .= '<div class="col-md-4 mb-4">';
            $html .= '<div class="card">';
            $html .= '<img src="' . $this->getImageUrl($product, 'thumbnail') . '" class="card-img-top" alt="' . $product->getName() . '">';
            $html .= '<div class="card-body">';
            $html .= '<h5 class="card-title">' . $product->getName() . '</h5>';
            $html .= '<h3>Booked' . $contributionBooked . '%</h3>';
            $html .= '<div class="contribution-booked-bar-container">
                          <progress class="contribution-booked-bar" value="' . $contributionBooked .'" max="100"></progress>
                       </div>';
            $html .= '<p class="card-text">' . $this->getFormattedPriceWithCurrency($product->getFinalPrice()) . '</p>';
            $html .= '<a href="' . $this->getProductUrl($product) . '" class="btn btn-primary">Book Now</a>';
            // Add more product information as needed
            $html .= '</div></div></div>';
        }
        $html .= '</div>';

        return $html;
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
}
