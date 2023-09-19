<?php

namespace BzoTech\Megashop\Block\Html;

class Breadcrumbs extends \Magento\Theme\Block\Html\Breadcrumbs
{
    /**
     * @return array
     */
    public function getCrumbs()
    {
        return $this->_crumbs;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
}
