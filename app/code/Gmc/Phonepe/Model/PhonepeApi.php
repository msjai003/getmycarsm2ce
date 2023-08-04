<?php


namespace Gmc\Phonepe\Model;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class PhonepeApi
{
    const MID = 'GETMYCARSONLINE';
    //const SALTKEY = '695d0547-3728-4b1c-825d-996479133615';
    const SALTKEY = 'e40c2417-2cea-474d-94d4-94f9a2d59f19';
    const SALTINDEX = 1;

    protected $client;
    public function __construct() {
        $this->client = new Client();
    }

    /**
     * @param $order
     * @return mixed
     * @throws \Exception
     */
    public function initiatePayment($order)
    {
        //$url = 'https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay';

        $url = 'https://api.phonepe.com/apis/hermes/pg/v1/pay';

        $payload = [
            'merchantId' => self::MID,
            'merchantTransactionId' => $order->getIncrementId(),
            'merchantUserId' => $order->getCustomerId(),
            'amount' => 100,
            'redirectMode' => 'POST',
            'redirectUrl' => 'https://magento.test/redirect-url',
            'callbackUrl' => 'https://magento.test/callback-url',
            'mobileNumber' => '6366761616',
            'paymentInstrument' => [
                'type' => 'PAY_PAGE'
            ]
        ];

        $payloadJson = json_encode($payload);

        $payloadEncoded = base64_encode($payloadJson);

        $checkSum = $this->generateSHA256Hash(
            $payloadEncoded,
            self::SALTKEY,
            self::SALTINDEX
        );

        $requestData = [
            'request' => $payloadEncoded
        ];

        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-VERIFY' => $checkSum,
                ],
                'json' => $requestData,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            //return $responseData;

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
