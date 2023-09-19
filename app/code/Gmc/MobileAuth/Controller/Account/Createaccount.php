<?php

namespace Gmc\MobileAuth\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\Message\ManagerInterface as MessageInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Gmc\MobileAuth\Model\OtpRepository;
use Magento\Framework\App\Request\Http;

class Createaccount implements ActionInterface
{
    public function __construct(
        private JsonFactory $resultJsonFactory,
        private CustomerFactory $customerFactory,
        private AccountManagementInterface $accountManagement,
        private Session $customerSession,
        private CustomerInterfaceFactory $customerInterfaceFactory,
        private OtpRepository $otpRepository,
        private MessageInterface $messageInterface,
        private Http $request
    ) {
    }


    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $postData = $this->request->getPostValue();
        $result['success'] = false;
        try {
        
            //Validate OTP
            $this->validateOtp(
                $postData['mobile_number'],
                $postData['otp']
            );
            $email = sprintf('%s@example.com', $postData['mobile_number']);
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

            $result = [
                'success' => true,
                'redirect' => true
            ];
        } catch (LocalizedException $e) {
            $result['message'] = $e->getMessage();
            $this->messageInterface->addErrorMessage(__($e->getMessage()));            
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $this->messageInterface->addErrorMessage(__($e->getMessage()));
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
            throw new LocalizedException(__('Invalid OTP. Please try again.'));
        }
    }
}
