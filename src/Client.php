<?php

namespace Avidian\Semaphore;

use Avidian\Semaphore\Exceptions\SemaphoreException;
use GuzzleHttp\Client as GuzzleHttpClient;

class Client
{
    const BASE_URL = 'https://api.semaphore.co/api/v4/';

    public $apikey;
    public $senderName = null;
    protected $client;

    /**
     * SemaphoreClient constructor.
     * @param $apikey
     * @param array $options ( e.g. sendername, baseUrl )
     */
    public function __construct($apikey, array $options = [])
    {
        $this->apikey = $apikey;

        $this->senderName = 'SEMAPHORE';
        if (isset($options['sendername'])) {
            $this->senderName = $options['sendername'];
        }

        $baseUrl = static::BASE_URL;
        if (isset($options['baseUrl'])) {
            $baseUrl = $options['baseUrl'];
        }
        $this->client = new GuzzleHttpClient(['base_uri' => $baseUrl, 'query' => ['apikey' => $this->apikey]]);
    }

    /**
     * Check the balance of your account
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function balance()
    {
        $response = $this->client->get('account');
        return $response->getBody();
    }

    /**
     * Send SMS message(s)
     *
     * @param string|string[] $recipient
     * @param string $message - The message you want to send
     * @param string|null $sendername
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \Avidian\Semaphore\Exceptions\SemaphoreException
     */
    public function send($recipient, $message, $sendername = null)
    {

        $recipients = is_array($recipient) ? $recipient : explode(',', $recipient);
        if (count($recipients) > 1000) {
            throw new SemaphoreException('API is limited to sending to 1000 recipients at a time');
        }

        $params = [
            'form_params' => [
                'apikey' =>  $this->apikey,
                'message' => $message,
                'number' => $recipient,
                'sendername' => $this->senderName
            ]
        ];

        if ($sendername !== null) {
            $params['form_params']['sendername'] = $sendername;
        }

        $response = $this->client->post('messages', $params);

        return $response->getBody();
    }

    /**
     * Retrieves data about a specific message
     *
     * @param $messageId - The encoded ID of the message
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function message($messageId)
    {
        $params = [
            'query' => [
                'apikey' =>  $this->apikey,
            ]
        ];
        $response = $this->client->get('messages/' . $messageId, $params);
        return $response->getBody();
    }

    /**
     * Retrieves up to 100 messages, offset by page
     * @param array $options ( e.g. limit, page, startDate, endDate, status, network, sendername )
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function messages($options)
    {

        $params = [
            'query' => [
                'apikey' =>  $this->apikey,
                'limit' => 100,
                'page' => 1
            ]
        ];

        //Set optional parameters
        if (array_key_exists('limit', $options)) {
            $params['query']['limit'] = $options['limit'];
        }

        if (array_key_exists('page', $options)) {
            $params['query']['page'] = $options['page'];
        }

        if (array_key_exists('startDate', $options)) {
            $params['query']['startDate'] = $options['startDate'];
        }

        if (array_key_exists('endDate', $options)) {
            $params['query']['endDate'] = $options['endDate'];
        }

        if (array_key_exists('status', $options)) {
            $params['query']['status'] = $options['status'];
        }

        if (array_key_exists('network', $options)) {
            $params['query']['network'] = $options['network'];
        }

        if (array_key_exists('sendername', $options)) {
            $params['query']['sendername'] = $options['sendername'];
        }

        $response = $this->client->get('messages', $params);
        return $response->getBody();
    }

    /**
     * Get account details
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function account()
    {
        $response = $this->client->get('account');
        return $response->getBody();
    }

    /**
     * Get users associated with the account
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function users()
    {
        $response = $this->client->get('account/users');
        return $response->getBody();
    }

    /**
     * Get sender names associated with the account
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function senderNames()
    {
        $response = $this->client->get('account/sendernames');
        return $response->getBody();
    }

    /**
     * Get transactions associated with the account
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function transactions()
    {
        $response = $this->client->get('account/transactions');
        return $response->getBody();
    }
}
