<?php
namespace Gmc\MobileAuth\Controller\Account;

use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Url;

class Register extends Action
{
    protected $resultPageFactory;
    protected $customerSession;
    protected $urlModel;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Session $customerSession,
        Url $urlModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->urlModel = $urlModel;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }
}
