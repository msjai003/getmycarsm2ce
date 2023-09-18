<?php

namespace Gmc\MobileAuth\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Gmc\MobileAuth\Model\OtpRepository;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Message\ManagerInterface;
use Magento\Customer\Model\ResourceModel\Customer\Collection;

class LoginPost implements ActionInterface
{
    public function __construct(
        private JsonFactory $resultJsonFactory,
        private CustomerFactory $customerFactory,
        private AccountManagementInterface $accountManagement,
        private Session $customerSession,
        private CustomerInterfaceFactory $customerInterfaceFactory,
        private OtpRepository $otpRepository,
        private Http $request,
        private ManagerInterface $messageInterface,
        private Collection $collection
    ) {

    }


    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $postData = $this->request->getPostValue();
        $result = ['success' => false, 'message' => ''];
        $mobileNumber = (int) $postData['mobile_number_login'] ?? '';
        try {
            if(empty($mobileNumber) || !preg_match('/^[0-9]{10}+$/', $mobileNumber)) {
                throw new LocalizedException(__('Invalid mobile number!'));
            }
            $collection = $this->collection
            ->addAttributeToFilter('mobile_number', $mobileNumber);
            $size = $collection->getSize();
            if (!$size) {
                throw new LocalizedException(__('Mobile number does not exist!'));
            }
            $customer = $collection->getFirstItem();

            // Set the customer session as logged in
            $this->customerSession->setCustomerAsLoggedIn($customer);

            $result = ['success' => true];
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $this->messageInterface->addErrorMessage(__($msg));
        }

        return $resultJson->setData($result);
    }
}
