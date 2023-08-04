<?php

namespace Gmc\Phonepe\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Gmc\Phonepe\Model\PhonePeApi;

class PlaceOrderAfterObserver implements ObserverInterface
{
    protected $phonePeApi;

    public function __construct(
        PhonePeApi $phonePeApi
    ) {
        $this->phonePeApi = $phonePeApi;
    }

    public function execute(Observer $observer)
    {
        // Get the order object
        $order = $observer->getEvent()->getOrder();

        // Replace 'payment_method_code' with the actual payment method code for PhonePe
        if ($order->getPayment()->getMethod() == 'checkmo') {
            // Initiate the PhonePe payment request
            $phonePePaymentUrl = $this->phonePeApi->initiatePayment($order);

            // Redirect the customer to the PhonePe payment URL
            if (isset($phonePePaymentUrl)) {
                header('Location: ' . $phonePePaymentUrl);
                exit();
            } else {
                // Handle error if payment initiation fails
            }
        }
    }
}
