<?php

namespace FrontBundle\Service;

use GuzzleHttp\Client;

class ClientService
{
    protected $client;

    protected $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }

    public function initClient()
    {
        $this->client = new Client();
        $this->client->setDefaultOption('headers/Accept', 'application/json');
    }

    public function get($url, $options = array())
    {
        if (empty($this->client)) {
            $this->initClient();
        }

        $res = $this->client->get($url, $options);
        $body = $res->getBody();
        $data = json_decode($body, true);

        $this->logger->info('GET to ' . $url . ' with status code: ' . $res->getStatusCode());
        return $data;
    }

    public function delete($url, $options = array())
    {
        if (empty($this->client)) {
            $this->initClient();
        }

        $res = $this->client->delete($url, $options);
        $code = $res->getStatusCode();
        $this->logger->info('DELETE to ' . $url . ' with status code: ' . $code);

        return $code;
    }

    public function post($url, $data = '')
    {
        if (empty($this->client)) {
            $this->initClient();
            $this->client->setDefaultOption('headers/Content-type', 'application/json');
        }

        $res = $this->client->post($url, ['body' => $data]);
        $this->logger->info('POST to ' . $url . ' with status code: ' . $res->getStatusCode());
        return $res;
    }
}