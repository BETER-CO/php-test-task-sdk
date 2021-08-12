<?php

namespace TestTaskSdkClient;

use Exception;
use http\Encoding\Stream;

class TTClient
{
    private $payload;
    private $endpoint;

    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): bool
    {
        if (empty($this->payload)) {
            throw new Exception('Payload not set.');
        }
        if (empty($this->endpoint)) {
            throw new Exception('Endpoint not set.');
        }

        return $this->internalSend();
    }

    /**
     * @throws Exception
     */
    private function internalSend(): bool
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "payload=" . urlencode(json_encode($this->payload)));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 90);
        $server_output = curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($responseCode !== 200) {
            throw new Exception('Error while trying to send message.');
        }
        $this->payload = null;

        return true;
    }
}