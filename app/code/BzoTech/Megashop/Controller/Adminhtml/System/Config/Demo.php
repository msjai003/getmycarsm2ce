<?php

namespace BzoTech\Megashop\Controller\Adminhtml\System\Config;

abstract class Demo extends \Magento\Backend\App\Action
{
    protected function _import()
    {
        return $this->_objectManager->get('BzoTech\Megashop\Model\Import\Demo')
            ->importDemo($this->getRequest()->getParam('demo_version'), $this->getRequest()->getParam('current_store'), $this->getRequest()->getParam('current_website'));
    }
}
