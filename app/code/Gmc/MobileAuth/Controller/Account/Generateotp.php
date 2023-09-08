<?php

namespace Gmc\MobileAuth\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\PhpEnvironment\Request;
use Magento\Framework\Math\Random;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Stdlib\CookieManagerInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\Validator\DataObject;
use Magento\Framework\App\ObjectManager;
use Gmc\MobileAuth\Model\OtpRepository;
use Magento\Framework\View\Result\PageFactory;
use Gmc\Sms\Model\SendSms; // Replace with your SMS module

class Generateotp extends Action
{
    protected $customerFactory;
    protected $resultJsonFactory;
    protected $customerSession;
    protected $formKeyValidator;
    protected $cookieMetadataManager;
    protected $mathRandom;
    protected $dataObjectValidator;
    protected $messageManager;
    protected $urlInterface;
    protected $accountManagement;
    protected $request;
    private $random;
    protected $otpRepository;
    protected $resultPageFactory;
    protected $smsClient;

    public function __construct(
        Context $context,
        CustomerFactory $customerFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Customer\Model\Session $customerSession,
        Validator $formKeyValidator,
        CookieManagerInterface $cookieMetadataManager,
        Random $mathRandom,
        DataObject $dataObjectValidator,
        ManagerInterface $messageManager,
        UrlInterface $urlInterface,
        AccountManagementInterface $accountManagement,
        Request $request,
        OtpRepository $otpRepository,
        PageFactory $resultPageFactory,
        SendSms $smsClient // Replace with your SMS module
    ) {
        parent::__construct($context);
        $this->customerFactory = $customerFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->customerSession = $customerSession;
        $this->formKeyValidator = $formKeyValidator;
        $this->cookieMetadataManager = $cookieMetadataManager;
        $this->mathRandom = $mathRandom ?: ObjectManager::getInstance()->get(Random::class);
        $this->dataObjectValidator = $dataObjectValidator;
        $this->messageManager = $messageManager;
        $this->urlInterface = $urlInterface;
        $this->accountManagement = $accountManagement;
        $this->request = $request;
        $this->otpRepository = $otpRepository;
        $this->resultPageFactory = $resultPageFactory;
        $this->smsClient = $smsClient;


    }

    public function execute()
    {
        $mobile = $this->getRequest()->getParam('mobile_number');
        $this->customerSession->setMobileNumber($mobile);

        //Otp generation
        $generatedOtp = $this->mathRandom->getRandomNumber(100000, 999999);
        $expirationTime = date('Y-m-d H:i:s', strtotime('+5 minutes'));
        $this->otpRepository->saveOtp($mobile, $generatedOtp, $expirationTime);

        //Send Otp
        $this->customerSession->setMobileOtp($generatedOtp);
        $resultJson = $this->resultJsonFactory->create();
        $response = $this->sendOtp($mobile, $generatedOtp);
        return $resultJson->setData($response);
    }

    private function sendOtp($mobile, $generatedOtp)
    {
        try {
            return $this->smsClient->sendSms(
                'otp',
                $mobile,
                $generatedOtp
            );
        } catch (\Exception $e) {
            echo (string)$e->getMessage();
        }
    }
}
