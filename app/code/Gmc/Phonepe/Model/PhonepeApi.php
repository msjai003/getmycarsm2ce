<?php

namespace Gmc\Phonepe\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Gmc\Phonepe\Model\Helper\Data;
use Gmc\Phonepe\Logger\Logger;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;

class PhonepeApi
{
    protected $client;
    protected $helper;
    protected $logger;
    protected $redirectFactory;

    public function __construct(
        Client $client,
        Data $helper,
        Logger $logger,
        RedirectFactory $redirectFactory
    ) {
        $this->client = $client;
        $this->helper = $helper;
        $this->logger = $logger;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @param $order
     * @return Redirect
     * @throws \Exception
     */
    public function initiatePayment($order)
    {

        $payload = [
            'merchantId' => $this->helper->getProductionMid(),
            'merchantTransactionId' => $order->getId(),
            'merchantUserId' => $order->getCustomerId(),
            'amount' => $this->helper->rupeeToPaisa($order->getGrandTotal()),
            'redirectMode' => 'POST',
            'redirectUrl' => $this->helper->getRedirectUrl(),
            'callbackUrl' => $this->helper->getCallbackUrl(),
            'mobileNumber' => '',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $payloadJson = json_encode($payload);
        $payloadEncoded = base64_encode($payloadJson);
        $checkSum = $this->generateSHA256Hash(
            $payloadEncoded,
            $this->helper->getProductionSaltKey(),
            $this->helper->getProductionSaltIndex()
        );

        $requestData = [
            'request' => $payloadEncoded
        ];

        try {
            $response = $this->client->post(
                $this->helper->getProductionUrl(),
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'X-VERIFY' => $checkSum,
                    ],
                    'json' => $requestData,
                ]
            );

            $responseData = json_decode($response->getBody()->getContents(), true);

            $this->logger->info("API Response:" . $responseData);

            if (isset($responseData['success']) && $responseData['success'] === false) {
                $this->logger->error("API request was not successful: " . $responseData['message']);
                throw new \Exception("API request was not successful: " . $responseData['message']);
            }

            $redirectUrl = $responseData['data']['instrumentResponse']['redirectInfo']['url'];

            return $redirectUrl;

        } catch (GuzzleException $e) {
            $this->logger->error("Guzzle HTTP error: " . $e->getMessage());
            throw new \Exception("Guzzle HTTP error: " . $e->getMessage());
        } catch (\Exception $e) {
            $this->logger->error("Other error: " . $e->getMessage());
            throw new \Exception("Other error: " . $e->getMessage());
        }
    }

    protected function generateSHA256Hash($payloadEncoded, $saltKey, $saltIndex)
    {
        $dataToHash = $payloadEncoded . "/pg/v1/pay" . $saltKey;
        $hash = hash('sha256', $dataToHash);
        $finalHash = $hash . "###" . $saltIndex;
        return $finalHash;
    }
}
