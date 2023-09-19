<?php

namespace BzoTech\ThemeCore\Block;

class Template extends \Magento\Framework\View\Element\Template
{
    public $_coreRegistry;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        $objectManager        = \Magento\Framework\App\ObjectManager::getInstance();
        $helper_config        = $objectManager->get('BzoTech\ThemeCore\Helper\Data');
        $enableLadyloading    = $helper_config->getCoreConfig('advanced/lazyload_group/enable_ladyloading');
        $enableStickyMenu     = $helper_config->getCoreConfig('general/navigation_group/sticky_menu');
        $showVerticalMenuHome = $helper_config->getCoreConfig('home_page/show_dropdown_vertical_menu');
        $bgHomeColor          = $helper_config->getCoreConfig('home_page/home_background_color');
        $bgDefaultColor       = $helper_config->getCoreConfig('general/background_group/body_background_color');

        if ($enableLadyloading) {
            $this->pageConfig->addBodyClass('enable-ladyloading');
        }

        if ($enableStickyMenu) {
            $this->pageConfig->addBodyClass('enable-stickymenu');
        }

        if ($bgDefaultColor === 'FFFFFF') {
            $this->pageConfig->addBodyClass('bg-default-white');
        } else {
            $this->pageConfig->addBodyClass('bg-default-color');
        }

        if ($bgHomeColor === 'FFFFFF') {
            $this->pageConfig->addBodyClass('bg-home-white');
        } else {
            $this->pageConfig->addBodyClass('bg-home-color');
        }

        if ($showVerticalMenuHome) {
            $this->pageConfig->addBodyClass('show-dropdown-vertical');
        }

        return parent::_prepareLayout();
    }
}
