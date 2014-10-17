<?php

/**
 * Class NexmoSms
 *
 * Use Nexmo.com API to send sms
 * @author Alexandru Bugarin <alexandru.bugarin@gmail.com>
 */
class NexmoSms {

    /**
     * @var string Nexmo account API key
     */
    private $_apiKey = "";

    /**
     * @var string Nexmo account API secret
     */
    private $_apiSecret = "";

    /**
     * @var string Nexmo server URL
     */
    private $_nexmoUrl = "https://rest.nexmo.com/sms/json";

    /**
     * @var array SMS sending options
     */
    private $_params = array();

    /**
     * @param string $apiKey
     * @param string $apiSecret
     */
    public function __construct($apiKey, $apiSecret) {
        $this->_apiKey = $apiKey;
        $this->_apiSecret = $apiSecret;
    }

    /**
     * @param array $params key=>value array with nexmo sms options
     */
    public function setSMSParams($params) {

        // Remove not allowed parameters
        $allowedParams = array(
            'type',
            'status-report-req',
            'client-ref',
            'network-code',
            'vcard',
            'vcal',
            'ttl',
            'message-class',
            'udh'
        );
        $invalidParams = array_diff_key($params, array_flip($allowedParams));
        foreach (array_keys($invalidParams) as $key) {
            unset($params[$key]);
        }

        $this->_params = $params;
    }

    /**
     * @param string $from
     * @param string $to
     * @param string $content
     * @throws Exception
     * @return Nexmo json response on success
     */
    public function sendSMS($from, $to, $content) {

        $this->_params['api_key'] = $this->_apiKey;
        $this->_params['api_secret'] = $this->_apiSecret;
        $this->_params['from'] = $from;
        $this->_params['to'] = $to;

        if (!$this->_params['type'] || $this->_params['type'] === 'text') {

            $this->_params['text'] = urlencode(utf8_encode($content));
        } else if ($this->_params['type'] === 'binary') {

            $this->_params['body'] = bin2hex($content);
        }

        $fieldsString = "";
        foreach ($this->_params as $key => $value) {
            $fieldsString .= $key.'='.$value.'&';
        }
        rtrim($fieldsString, '&');

        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded'
        ));
        curl_setopt($ch, CURLOPT_URL, $this->_nexmoUrl);
        curl_setopt($ch, CURLOPT_POST, count($this->_params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $result = json_decode(curl_exec($ch));

        foreach ($result->messages[0] as $key => $value) {
            if ($key === 'error-text') {
                throw new Exception($value.' Status: '.$result->messages[0]->status);
            }
        }

        return $result;
    }
}