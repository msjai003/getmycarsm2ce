<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   Webkul_Pwa
 * @author    Webkul Software Private Limited
 * @copyright  Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace Webkul\Pwa\Controller\Adminhtml\Support;

use Magento\Backend\App\Action;

class Storereview extends Action
{
    /**
     * Support Userguide Link.
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl("https://store.webkul.com/magento2-progressive-web-app.html#reviews");
        return $resultRedirect;
    }
}
