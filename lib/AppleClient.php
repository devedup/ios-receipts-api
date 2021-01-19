<?php

namespace Busuu\IosReceiptsApi;

use Exception;
use GuzzleHttp\Client;

class AppleClient
{
    /** @var  Client $client */
    private $client;
    /** @var  string */
    private $password;
    private $excludeOldTransactions;

    public function __construct($password, bool $excludeOldTransactions)
    {
        $this->client = new Client();
        $this->password = $password;
        $this->excludeOldTransactions = $excludeOldTransactions;
    }

    /**
     * Fetch the receipt from apple
     *
     * @param $receiptData
     * @param $endpoint
     *
     * @return array
     * @throws Exception
     */
    public function fetchReceipt($receiptData, $endpoint)
    {
        $data = [
            'password' => $this->password,
            'receipt-data' => $receiptData,
            'exclude-old-transactions' => $this->excludeOldTransactions
        ];

        $response = $this->client->post($endpoint, [
            'body' => json_encode($data),
            'timeout' => 20
        ]);

        $jsonResponse = json_decode($response->getBody(), true);

        if (null !== $jsonResponse) {
            return $jsonResponse;
        }

        throw new Exception(sprintf('Invalid Response from Apple Server: %s', $response->getBody()));
    }
}
