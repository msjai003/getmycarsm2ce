<?php

namespace BzoTech\Megashop\Block;

use Magento\Customer\Model\Context;

class Header extends \Magento\Framework\View\Element\Template
{
    protected $_logo;
    protected $httpContext;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        array $data = []
    )
    {
        $this->_logo = $logo;
        parent::__construct($context, $data);
        $this->httpContext = $httpContext;
    }


    /**
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->_logo->getLogoAlt();
    }

    /**
     * @return bool
     */

    public function isHomePage()
    {
        return $this->_logo->isHomePage();
    }

    public function isLoggedIn()
    {
        return $this->httpContext->getValue(Context::CONTEXT_AUTH);
    }
}
