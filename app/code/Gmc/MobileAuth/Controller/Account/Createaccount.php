<?php

namespace Gmc\MobileAuth\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Gmc\MobileAuth\Model\OtpRepository;

class Createaccount extends Action
{
    public function __construct(
        Context $context,
        private JsonFactory $resultJsonFactory,
        private CustomerFactory $customerFactory,
        private AccountManagementInterface $accountManagement,
        private Session $customerSession,
        private CustomerInterfaceFactory $customerInterfaceFactory,
        private OtpRepository $otpRepository
    ) {
        parent::__construct($context);
    }


    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $postData = $this->getRequest()->getPostValue();

        //Validate OTP
        $result = $this->validateOtp(
            $postData['mobile_number'],
            $postData['otp']
        );

        if (empty($result) && !$result) {
            $result = ['success' => false, 'message' => 'Invalid OTP. Please try again.'];
            return $resultJson->setData($result);
        }

        $postData = $this->getRequest()->getPostValue();
        $email = sprintf('%s@example.com', $postData['mobile_number']);
        try {

            $customer = $this->customerInterfaceFactory->create();
            $customer->setFirstname($postData['firstname']);
            $customer->setLastname($postData['lastname']);
            $customer->setEmail($email);
            $customer->setCustomAttribute('mobile_number', $postData['mobile_number']);
            $customer->setGroupId(4);

            // Create a new customer account
            $_customer = $this->accountManagement->createAccount($customer);
            $customerId = $_customer->getId(); // Assuming you have the customer ID
            $customer = $this->customerFactory->create()->load($customerId);

            // Set the customer session as logged in
            $this->customerSession->setCustomerAsLoggedIn($customer);

            $result = ['success' => true];
        } catch (LocalizedException $e) {
            // Handle registration error, e.g., email already exists
            $result = ['success' => false, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            // Handle other exceptions or errors
            $result = ['success' => false, 'message' => 'Registration failed.'];
        }

        return $resultJson->setData($result);
    }

    /**
     * @param $mobile
     * @param $otp
     * @return bool
     */
    private function validateOtp($mobile, $otp)
    {
        $otpRecord = $this->otpRepository->getOtpByMobile($mobile);
        if ($otpRecord->getId() && $otpRecord->getData('otp_code') === $otp) {
            //$this->otpRepository->deleteOtp($otpRecord->getId());
            //$this->customerSession->unsMobileOtp();
            return true;
        } else {
            // Redirect back to the OTP verification form.
            // You can customize the URL as needed.
            return false;
        }
    }
}
