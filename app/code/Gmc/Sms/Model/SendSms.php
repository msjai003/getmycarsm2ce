<?php


namespace Gmc\Sms\Model;

use GuzzleHttp\Client;
use Gmc\Sms\Model\Helper\Data;

class SendSms
{
    protected $client;

    protected $helper;

    /**
     * @param Client $client
     * @param \Gmc\Sms\Model\Helper\Data $helper
     */
    public function __construct(
        Client $client,
        Data $helper
    ) {
        $this->client = $client;
        $this->helper = $helper;
    }

    /**
     * @param $mode
     * @param $number
     * @param $generatedOtp
     * @return string
     */
    public function sendSms($mode, $number, $generatedOtp)
    {
        //check Mode and use appropriate template
        $message = $this->pickTemplate($mode, $generatedOtp);

        $apiKey = $this->helper->getApiKey();
        $sender = $this->helper->getSenderId();

        $data = [
            'apikey' => $apiKey,
            'numbers' => $number,
            'sender' => $sender,
            'message' => $message,
        ];

        $response = $this->sendRequest($data);

        return $response;
    }

    protected function sendRequest($data)
    {
        $url = $this->helper->getBaseUrl() . $this->helper->getEndpoint();

        try {
            $response = $this->client->post($url, [
                'form_params' => $data,
            ]);

            return $response->getBody()->getContents();
        } catch (\Exception $e) {
            // Handle exceptions here
            return $e->getMessage();
        }
    }

    private function pickTemplate($mode, $generatedOtp)
    {
        switch ($mode)
        {
            case 'otp':
                $otpMessage = str_replace('{{otp}}', $generatedOtp, $this->helper->getOTPMsg());
                return $otpMessage;
                break;
            case 'register':
                return $this->helper->getRegisterMsg();
                break;
            default:
                //Do Nothing
        }
    }

}
