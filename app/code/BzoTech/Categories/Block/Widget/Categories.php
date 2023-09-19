<?php

namespace BzoTech\Categories\Block\Widget;

use Magento\Widget\Block\BlockInterface;

class Categories extends \Magento\Framework\View\Element\Template implements BlockInterface
{
    //protected $_template = "widget/grid.phtml";

    protected $_config = null;
    protected $_categoryFactory;
    protected $_categoriesHelper;
    protected $_categoryHelper;
    protected $_categoryRepository;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \BzoTech\Categories\Helper\Data $categoriesHelper,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        array $data = []
    )
    {
        $this->_categoryFactory    = $categoryFactory;
        $this->_categoryHelper     = $categoryHelper;
        $this->_categoriesHelper   = $categoriesHelper;
        $this->_categoryRepository = $categoryRepository;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    protected function _toHtml()
    {
        $customTemplate = $this->getData('custom_template');
        $widgetStyle    = $this->getData('widget_style');
        if ($customTemplate) {
            $this->setTemplate($customTemplate);
        } else {
            if ($widgetStyle == 'grid') {
                $this->setTemplate('widget/grid.phtml');
            }

            if ($widgetStyle == 'slider') {
                $this->setTemplate('widget/slider.phtml');
            }

            if ($widgetStyle == 'tabs') {
                $this->setTemplate('widget/tab-grid.phtml');
            }
        }

        return parent::_toHtml();
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getCategoryCollection()
    {
        $catIds         = $this->getData('category_ids');
        $mediaBaseUrl   = $this->_categoriesHelper->getMediaUrl();
        $listCategories = [];
        !is_array($catIds) && $catIds = preg_split('/[\s|,|;]/', $catIds, -1, PREG_SPLIT_NO_EMPTY);

        if (!empty($catIds)) {
            foreach ($catIds as $catId) {
                $category = $this->_categoryFactory->create()->load($catId);
                if ($category->getIsActive()) {
                    $category->getUrl();

                    $imgCatUrl = $category->getBzotechCategoryImage();
                    $catImgUrl = "";

                    if ($imgCatUrl) {
                        $arr       = explode('/media/', $imgCatUrl);
                        $catImgUrl = $mediaBaseUrl . $arr[1];
                    }

                    $listCategories[$catId]                      = $category->__toArray();
                    $listCategories[$catId]['cat_image']         = $catImgUrl;
                    $listCategories[$catId]['product_count']     = $category->getProductCount();
                    $listCategories[$catId]['id']                = (int)$category->getId();
                    $listCategories[$catId]['short_description'] = $category->getBzotechCategoryDescription();
                }
            }
        }
        return $listCategories;
    }

    /**
     * @param $parentCatIds
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoryChildrenCollection($parentCatIds)
    {
        $parentCategory = $this->_categoryRepository->get($parentCatIds);
        $subCategories  = $parentCategory->getChildrenCategories()->setPageSize((int)$this->getData('sub_limit'));
        return $subCategories;
    }
}