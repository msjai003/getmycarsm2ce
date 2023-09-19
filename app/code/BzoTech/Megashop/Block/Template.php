<?php

namespace BzoTech\Megashop\Block;

class Template extends \Magento\Framework\View\Element\Template
{
    public $_coreRegistry;
    protected $_logo;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    )
    {
        $this->_coreRegistry = $coreRegistry;
        $this->_logo         = $logo;
        parent::__construct($context, $data);
    }

    public function _prepareLayout()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $page          = $objectManager->get('Magento\Framework\View\Page\Config');
        $themeConfig   = $objectManager->get('BzoTech\Megashop\Helper\Data');
        $headerStyle   = $themeConfig->getThemeConfig('theme_layout/layout_header/header_style');
        $productStyle  = $themeConfig->getThemeConfig('theme_layout/layout_product/product_style');
        $footerStyle   = $themeConfig->getThemeConfig('theme_layout/layout_footer/footer_style');
        $rtlLayout     = $themeConfig->getThemeConfig('theme_layout/direction_rtl');

        $this->pageConfig->addBodyClass($headerStyle . '-style');
        $this->pageConfig->addBodyClass($productStyle . '-style');
        $this->pageConfig->addBodyClass($footerStyle . '-style');

        if ($rtlLayout) {
            $extRtl = "_rtl";
            $page->addPageAsset('css/bootstrap_rtl.css');
            $this->pageConfig->addBodyClass('rtl-layout');
        } else {
            $extRtl = "";
            $page->addPageAsset('css/bootstrap.css');
        }

        $headerCss    = 'css/' . $headerStyle . $extRtl . '.css';
        $homeCss      = 'css/home' . $extRtl . '.css';
        $footerCss    = 'css/' . $footerStyle . $extRtl . '.css';
        $productCss   = 'css/' . $productStyle . $extRtl . '.css';
        $pageThemeCss = 'css/pages' . $extRtl . '.css';

        $page->addPageAsset($headerCss);
        $page->addPageAsset($homeCss);
        $page->addPageAsset($productCss);
        $page->addPageAsset($pageThemeCss);
        $page->addPageAsset($footerCss);

        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getLogoAlt()
    {
        return $this->_logo->getLogoAlt();
    }
}
