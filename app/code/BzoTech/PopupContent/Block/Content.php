<?php
/**
 * @category BZOTech
 * @package BzoTech_PopupContent
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\PopupContent\Block;

class Content extends \Magento\Framework\View\Element\Template
{
    protected $_scopeConfig;
    protected $_filterProvider;
    protected $_storeManager;

    public function __construct(
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Backend\Block\Template\Context $context,
        array $data = []
    )
    {
        $this->_scopeConfig    = $context->getScopeConfig();
        $this->_filterProvider = $filterProvider;
        parent::__construct($context, $data);
    }

    /**
     * @param string $value
     * @return string
     * @throws \Exception
     */

    public function getContent($value = '')
    {
        $html = $this->_filterProvider->getPageFilter()->filter($value);
        return $html;
    }
}