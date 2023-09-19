<?php

namespace BzoTech\Categories\Block;

class Categories extends \Magento\Framework\View\Element\Template
{
    protected $_config = null;
    protected $_coreRegistry = null;
    protected $_categoryFactory;
    protected $_categoryHelper;
    protected $_categoriesHelper;
    protected $_categoryRepository;
    protected $_categoryCollectionFactory;
    protected $storeManager;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\CategoryRepository $categoryRepository,
        \BzoTech\Categories\Helper\Data $categoriesHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Framework\Registry $registry,
        $data = []
    )
    {
        $this->_categoryFactory           = $categoryFactory;
        $this->_categoryHelper            = $categoryHelper;
        $this->_categoriesHelper          = $categoriesHelper;
        $this->_categoryRepository        = $categoryRepository;
        $this->storeManager               = $storeManager;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_coreRegistry              = $registry;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getCategoryCollection($isActive = true, $level = 2)
    {
        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');

        if ($isActive) {
            $collection->addIsActiveFilter();
        }

        if ($level) {
            $collection->addLevelFilter($level);
        }

        return $collection;
    }

    /**
     * @param $parentCatIds
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCategoryChildrenCollection($parentCatIds)
    {
        $parentCategory = $this->_categoryRepository->get($parentCatIds);
        $subLimit       = $this->_categoriesHelper->getCategoriesConfig('all_categories/sub_limit');
        $subCategories  = $parentCategory->getChildrenCategories()->setPageSize($subLimit);
        return $subCategories;
    }

    /**
     * @param $parentCatIds
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getSubCategoryCollection($parentCatIds)
    {
        $parentCategory = $this->_categoryRepository->get($parentCatIds);
        $subCategories  = $parentCategory->getChildrenCategories();
        return $subCategories;
    }

    /**
     * @param $imgSrc
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */

    public function getCategoryImage($imgSrc)
    {
        if ($imgSrc) {
            $imgPath = $imgSrc;
        } else {
            $imgPath = $this->_categoriesHelper->getMediaUrl() . 'bzotech_categories/no-photo.jpg';
        }

        return $imgPath;
    }

    /**
     * @return mixed
     */

    public function getCurrentCategory()
    {
        if (!$this->hasData('current_category')) {
            $this->setData('current_category', $this->_coreRegistry->registry('current_category'));
        }
        return $this->getData('current_category');
    }
}