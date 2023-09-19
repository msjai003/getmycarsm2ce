<?php
/**
 * @category BZOTech
 * @package BzoTech_ProductTabs
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\ProductTabs\Controller\Index;

use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    protected $jsonEncoder;
    protected $_layout;
    protected $response;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    private $cacheInterFace;

    private $_listingTabs;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\View\LayoutInterface $layout,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\App\Response\Http $response,
        \Magento\Framework\App\CacheInterface $cacheInterFace,
        \BzoTech\ProductTabs\Block\ProductTabs $listingTabs
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->jsonEncoder       = $jsonEncoder;
        $this->_layout           = $layout;
        $this->response          = $response;
        $this->cacheInterFace    = $cacheInterFace;
        $this->_listingTabs      = $listingTabs;
        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $isAjax = $this->getRequest()->isAjax();
        if ($isAjax) {
            $layout        = $this->_layout;
            $listingTabs   = $this->_listingTabs;
            $template_file = "BzoTech_ProductTabs::default_items.phtml";
            $listingTabs->setTemplate($template_file);
            $moduleManager        = $this->_objectManager->get('\Magento\Framework\Module\Manager');
            $this->cacheInterFace = $moduleManager->isEnabled('Magento_Csp') ? $this->_objectManager->get('Magento\Csp\Model\BlockCache') : $this->cacheInterFace;
            $cacheKey             = $listingTabs->getCacheKey();
            $cacheData            = $this->cacheInterFace->load($cacheKey);
            $result               = [];
            if ($cacheData) {
                $result['items_markup'] = $cacheData;
            } else {
                $layout->getUpdate()->load(['producttabs_index_ajax']);
                $layout->generateXml();
                $output                 = $layout->getOutput();
                $result['items_markup'] = $output;
            }
            return $this->getResponse()->representJson(
                $this->_objectManager->get('Magento\Framework\Json\Helper\Data')->jsonEncode($result)
            );
        }
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend(__('Product Tabs'));
        return $resultPage;
    }
}