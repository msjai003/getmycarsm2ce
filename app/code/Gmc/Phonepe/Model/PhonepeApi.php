<?php


namespace Gmc\Phonepe\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Gmc\Phonepe\Model\Helper\Data;

class PhonepeApi
{
    protected $client;

    protected $helper;

    /**
     * @param Client $client
     * @param Data $helper
     */
    public function __construct(
        Client $client,
        Data $helper
    ) {
        $this->client = $client;
        $this->helper = $helper;
    }

    /**
     * @param $order
     * @return mixed
     * @throws \Exception
     */
    public function initiatePayment($order)
    {
        $payload = [
            'merchantId' => $this->helper->getProductionMid(),
            'merchantTransactionId' => $order->getIncrementId(),
            'merchantUserId' => $order->getCustomerId(),
            'amount' => $this->helper->rupeeToPaisa($order->getGrandTotal()),
            'redirectMode' => 'POST',
            'redirectUrl' => $this->helper->getRedirectUrl(),
            'callbackUrl' => $this->helper->getCallbackUrl(),
            'mobileNumber' => NULL,
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
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            // Check if success is false and throw an exception
            if (isset($responseData['success']) && $responseData['success'] === false) {
                throw new \Exception("API request was not successful: " . $responseData['message']);
            }

            return $responseData['data']['instrumentResponse']['redirectInfo']['url'];

        } catch (GuzzleException $e) {
            throw new \Exception("Guzzle HTTP error: " . $e->getMessage());
        } catch (\Exception $e) {
            throw new \Exception("Other error: " . $e->getMessage());
        }
    }

    /**
     * @param $payloadEncoded
     * @param $saltKey
     * @param $saltIndex
     * @return string
     */
    protected function generateSHA256Hash($payloadEncoded, $saltKey, $saltIndex)
    {
        // Concatenate the payload, "/pg/v1/pay", and salt key
        $dataToHash = $payloadEncoded . "/pg/v1/pay" . $saltKey;

        // Calculate the SHA256 hash of the concatenated data
        $hash = hash('sha256', $dataToHash);

        // Concatenate the hash, "###", and salt index
        $finalHash = $hash . "###" . $saltIndex;

        return $finalHash;
    }

}
