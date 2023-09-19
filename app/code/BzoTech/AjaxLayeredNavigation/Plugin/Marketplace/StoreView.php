<?php
/**
 * @category BZOTech
 * @package BzoTech_AjaxLayeredNavigation
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\AjaxLayeredNavigation\Plugin\Marketplace;

use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\Page;

class StoreView
{
    /**
     * @var JsonFactory
     */
    protected $_resultJsonFactory;

    public function __construct(JsonFactory $resultJsonFactory)
    {
        $this->_resultJsonFactory = $resultJsonFactory;
    }

    /**
     * @param \Purpletree\Marketplace\Controller\Index\StoreView $subject
     * @param \Closure $method
     * @return \Magento\Framework\Controller\Result\Json|mixed
     */
    public function aroundExecute(\Purpletree\Marketplace\Controller\Index\StoreView $subject, \Closure $method)
    {
        $response = $method();
        if ($response instanceof Page) {
            if ($subject->getRequest()->getParam('ajax') == 1) {
                $subject->getRequest()->getQuery()->set('ajax', null);
                $requestUri = $subject->getRequest()->getRequestUri();
                $requestUri = preg_replace('/(\?|&)ajax=1/', '', $requestUri);
                $subject->getRequest()->setRequestUri($requestUri);
                $productsBlockHtml = $response->getLayout()->getBlock('aw_sbb.brand.products.list')->toHtml();
                $leftNavBlockHtml  = '';
                return $this->_resultJsonFactory->create()->setData(['success' => true, 'html' => [
                    'products_list' => $productsBlockHtml,
                    'filters' => $leftNavBlockHtml
                ]]);
            }
        }
        return $response;
    }
}
