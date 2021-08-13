<?php

namespace TestTaskSdkClient;

use Exception;

class TTClient
{
    const JOB_STATUS_DONE = 'done';
    const JOB_STATUS_IN_PROGRESS = 'in_progress';
    const JOB_STATUS_FAILED = 'failed';

    private $endpoint;
    /**
     * @var int
     */
    private $timeout = 0;

    public function __construct($endpoint, $token)
    {
        $this->endpoint = $endpoint;
        $this->token = $token;
    }

    public function setTimeout(int $timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function createJob($key, $payload): bool
    {
        sleep(2);

        return random_int(25, 543);
        //return $this->internalSend($key, $payload);
    }

    public function checkJobStatus($jobId)
    {
        sleep(1);

        return self::JOB_STATUS_DONE;
    }

    public function auth()
    {
        //TODO: add usage of $this->token
        sleep(1); // emulate send request

        return 'secure_app_key';
    }

    /**
     * @throws Exception
     */
    private function internalSend($key, $payload): bool
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "payload=" . urlencode(json_encode($payload))) . '&key=' . urlencode($key);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($responseCode !== 200) {
            throw new Exception('Error while trying to send message.');
        }
        $this->payload = null;

        return true;
    }
}