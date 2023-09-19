<?php

namespace BzoTech\WidgetProducts\Block\Widget;

use Magento\Widget\Block\BlockInterface;

class WidgetProducts extends \Magento\Catalog\Block\Product\AbstractProduct implements BlockInterface
{
    protected $_collection;
    protected $_resource;
    protected $_storeManager;
    protected $_storeId;
    protected $_catalogProductVisibility;
    protected $_objectManager;


    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $collection,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    )
    {
        $this->_objectManager            = $objectManager;
        $this->_collection               = $collection;
        $this->_resource                 = $resource;
        $this->_storeManager             = $context->getStoreManager();
        $this->_catalogProductVisibility = $catalogProductVisibility;
        $this->_storeId                  = (int)$this->_storeManager->getStore()->getId();
        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    protected function _toHtml()
    {
        $customTemplate = $this->getData('custom_template');
        $widgetStyle    = $this->getData('widget_style');
        if ($customTemplate) {
            $this->setTemplate($customTemplate);
        } else {
            $this->setTemplate('widget/grid.phtml');

            if ($widgetStyle == 'grid') {
                $this->setTemplate('widget/grid.phtml');
            }

            if ($widgetStyle == 'slider') {
                $this->setTemplate('widget/slider-grid.phtml');
            }

            if ($widgetStyle == 'slider_list') {
                $this->setTemplate('widget/slider-list.phtml');
            }
        }

        return parent::_toHtml();
    }

    /**
     * @return mixed
     */

    public function getProducts()
    {
        $product_source = $this->getData('product_source');
        switch ($product_source) {
            default:
            case 'lastest_products':
                return $this->getNewProducts();
                break;
            case 'best_sellers':
                return $this->getBestSellers();
                break;
            case 'special_products':
                return $this->getSpecialProducts();
                break;
            case 'featured_products':
                return $this->getFeaturedProducts();
                break;
            case 'countdown_products':
                return $this->getCountdownProducts();
                break;
        }
    }

    /**
     * @param null $type
     * @return bool|\Magento\Framework\View\Element\AbstractBlock|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    public function getDetailsRenderer($type = null)
    {
        if ($type === null || $type !== 'configurable') {
            $type = 'default';
            return null;
        }
        $rendererList = $this->getDetailsRendererList();
        if ($rendererList) {
            return $rendererList->getRenderer($type, 'default');
        }
        return null;
    }

    /**
     * @return bool|\Magento\Framework\View\Element\AbstractBlock|\Magento\Framework\View\Element\BlockInterface|\Magento\Framework\View\Element\RendererList
     * @throws \Magento\Framework\Exception\LocalizedException
     */

    protected function getDetailsRendererList()
    {
        return $this->getDetailsRendererListName() ? $this->getLayout()->getBlock(
            $this->getDetailsRendererListName()
        ) : $this->getChildBlock(
            $this->getNameInLayout() . '.details.renderers'
        );
    }

    /**
     * @return \Magento\Catalog\Block\Product\AbstractProduct|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        $this->getLayout()->addBlock(
            'Magento\Framework\View\Element\RendererList',
            $this->getNameInLayout() . '.renderlist',
            $this->getNameInLayout(),
            $this->getNameInLayout() . '.details.renderers'
        );
        $this->getLayout()->addBlock(
            'BzoTech\WidgetProducts\Block\Product\Renderer\Listing\Configurable',
            $this->getNameInLayout() . '.colorswatches',
            $this->getNameInLayout() . '.renderlist',
            'configurable'
        )->setTemplate('BzoTech_WidgetProducts::product/listing/renderer.phtml');
    }

    /**
     * @return mixed
     */
    private function getCountdownProducts()
    {
        $stringOrder = $this->getData('product_order_by');
        $count       = (int)$this->getData('product_limitation');
        $category_id = $this->getData('select_category');
        !is_array($category_id) && $category_id = preg_split('/[\s|,|;]/', $category_id, -1, PREG_SPLIT_NO_EMPTY);
        $connection = $this->_resource->getConnection();
        $collection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        $now        = date('Y-m-d H:i:s');
        $dateTo     = $this->getData('date_to');
        $dateToTime = date('Y-m-d H:i:s', strtotime($dateTo));
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->setStoreId($this->_storeId)
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('special_price', ['neq' => ''])
            ->addAttributeToFilter('special_from_date', ['lteq' => date('Y-m-d  H:i:s', strtotime($now))])
            ->addAttributeToFilter('special_to_date', ['lteq' => date('Y-m-d  H:i:s', strtotime($dateToTime))])
            ->addAttributeToFilter('is_saleable', ['eq' => 1], 'left');
        $collection->getSelect()->order('entity_id  ' . $stringOrder);
        if (!empty($category_id) && $category_id) {
            $collection->joinField(
                'category_id',
                $connection->getTableName($this->_resource->getTableName('catalog_category_product')),
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter(array(array('attribute' => 'category_id', 'in' => array($category_id))));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelect()->distinct(true)->group('e.entity_id')->limit($count);
        return $collection;
    }

    /**
     * @return mixed
     */
    private function getNewProducts()
    {
        $stringOrder = $this->getData('product_order_by');
        $count       = (int)$this->getData('product_limitation');
        $category_id = $this->getData('select_category');
        !is_array($category_id) && $category_id = preg_split('/[\s|,|;]/', $category_id, -1, PREG_SPLIT_NO_EMPTY);
        $connection = $this->_resource->getConnection();
        $collection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addUrlRewrite()
            ->setStoreId($this->_storeId)
            ->addAttributeToFilter('is_saleable', ['eq' => 1], 'left');
        $collection->getSelect()->order('entity_id  ' . $stringOrder);
        if (!empty($category_id) && $category_id) {
            $collection->joinField(
                'category_id',
                $connection->getTableName($this->_resource->getTableName('catalog_category_product')),
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter(array(array('attribute' => 'category_id', 'in' => array($category_id))));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelect()->distinct(true)->group('e.entity_id')->limit($count);
        return $collection;
    }

    /**
     * @return mixed
     */

    private function getSpecialProducts()
    {
        $stringOrder = $this->getData('product_order_by');
        $count       = (int)$this->getData('product_limitation');
        $category_id = $this->getData('select_category');
        !is_array($category_id) && $category_id = preg_split('/[\s|,|;]/', $category_id, -1, PREG_SPLIT_NO_EMPTY);
        $connection = $this->_resource->getConnection();
        $collection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        $now        = date('Y-m-d H:i:s');

        $collection->addMinimalPrice()
            ->addTaxPercents()
            ->addFinalPrice()
            ->addAttributeToSelect('*')
            ->addUrlRewrite()
            ->setStoreId($this->_storeId)
            ->addAttributeToFilter('special_price', ['neq' => ''])
            ->addAttributeToFilter('special_from_date', ['lteq' => date('Y-m-d  H:i:s', strtotime($now))])
            ->addAttributeToFilter('special_to_date', ['gteq' => date('Y-m-d  H:i:s', strtotime($now))])
            ->addAttributeToFilter('is_saleable', ['eq' => 1], 'left');
        $collection->getSelect()->order('entity_id  ' . $stringOrder);
        if (!empty($category_id) && $category_id) {
            $collection->joinField(
                'category_id',
                $connection->getTableName($this->_resource->getTableName('catalog_category_product')),
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter(array(array('attribute' => 'category_id', 'in' => array($category_id))));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelect()->distinct(true)->group('e.entity_id')->limit($count);
        return $collection;
    }

    /**
     * @return mixed
     */
    private function getFeaturedProducts()
    {
        $stringOrder = $this->getData('product_order_by');
        $count       = (int)$this->getData('product_limitation');
        $category_id = $this->getData('select_category');
        !is_array($category_id) && $category_id = preg_split('/[\s|,|;]/', $category_id, -1, PREG_SPLIT_NO_EMPTY);
        $connection = $this->_resource->getConnection();
        $collection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');

        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addUrlRewrite()
            ->setStoreId($this->_storeId)
            ->addAttributeToFilter('bzotech_featured', ['eq' => 1], 'left')
            ->addAttributeToFilter('is_saleable', ['eq' => 1], 'left');
        $collection->getSelect()->order('entity_id  ' . $stringOrder);
        if (!empty($category_id) && $category_id) {
            $collection->joinField(
                'category_id',
                $connection->getTableName($this->_resource->getTableName('catalog_category_product')),
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter(array(array('attribute' => 'category_id', 'in' => array($category_id))));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelect()->distinct(true)->group('e.entity_id')->limit($count);
        return $collection;
    }

    /**
     * @return mixed
     */

    private function getBestSellers()
    {
        $stringOrder = $this->getData('product_order_by');
        $count       = (int)$this->getData('product_limitation');
        $category_id = $this->getData('select_category');
        !is_array($category_id) && $category_id = preg_split('/[\s|,|;]/', $category_id, -1, PREG_SPLIT_NO_EMPTY);
        $connection = $this->_resource->getConnection();
        $collection = $this->_objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Collection');
        $collection->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addAttributeToSelect('*')
            ->addUrlRewrite()
            ->setStoreId($this->_storeId)
            ->addAttributeToFilter('is_saleable', ['eq' => 1], 'left');

        if (!empty($category_id) && $category_id) {
            $collection->joinField(
                'category_id',
                $connection->getTableName($this->_resource->getTableName('catalog_category_product')),
                'category_id',
                'product_id=entity_id',
                null,
                'left'
            )->addAttributeToFilter(array(array('attribute' => 'category_id', 'in' => array($category_id))));
        }
        $collection->setVisibility($this->_catalogProductVisibility->getVisibleInCatalogIds());
        $collection->getSelect()->distinct(true)->group('e.entity_id');
        $collection->getSelect()
            ->joinLeft(['soi' => $connection->getTableName($this->_resource->getTableName('sales_order_item'))], 'soi.product_id = e.entity_id', ['SUM(soi.qty_ordered) AS ordered_qty'])
            ->join(['order' => $connection->getTableName($this->_resource->getTableName('sales_order'))], "order.entity_id = soi.order_id", ['order.state'])
            ->where("order.state <> 'canceled' and soi.parent_item_id IS NULL AND soi.product_id IS NOT NULL")
            ->group('soi.product_id')
            ->order('ordered_qty ' . $stringOrder)
            ->limit($count);
        return $collection;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->_objectManager->get('\Magento\Framework\Url\Helper\Data')->getEncodedUrl($url),
            ]
        ];
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getImageThumbs(\Magento\Catalog\Model\Product $_product)
    {
        $product_thumbs = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($_product->getId())->getMediaGalleryImages();
        $image_thumb_width=82;
        $image_thumb_height=82;
        $image_large_width=380;
        $image_large_height=376;
        $imageHelper  = $this->_objectManager->get('\Magento\Catalog\Helper\Image');
        $thumbs=array();
        foreach($product_thumbs as $_image) {
            $thumb_url=$imageHelper->init($_product, 'product_page_image_small')->setImageFile($_image->getFile())->resize($image_thumb_width, $image_thumb_height)->getUrl();
            $main_url= $imageHelper->init($_product, 'product_page_image_large')->setImageFile($_image->getFile())->resize($image_large_width, $image_large_height)->getUrl();
            $thumbs[]=
            [
                'image_thumb_url' => $thumb_url,
                'image_large_url' => $main_url
            ];  
        }

        return $thumbs;
    }
    
}