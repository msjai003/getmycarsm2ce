<?php
/**
 * @category BZOTech
 * @package BzoTech_AjaxSearch
 * @version 1.0.0
 * @copyright Copyright (c) 2022 BZOTech Company. All Rights Reserved.
 * @license - Copyrighted Commercial Software
 * @author BZOTech Company  
 *   
 */

namespace BzoTech\AjaxSearch\Controller\Ajax;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\ResultFactory;

class Trending extends Action
{
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
    }
}