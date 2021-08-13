# TestTaskSDK for notifications

Here is the package for PHP language to work with TestTaskSDK notification service.

To install this package just add to your composer.json the following data:

```
{
    "require": {
        "beter/test-task-sdk": "1.*"
    }
}
```

After that, run `php composer.phar update`. That's all.

## Usage

To start using of TestTaskSDK service SDK write the following code in your application.

```
<?php

use TestTaskSdkClient\TTClient;

$endpoint = '...'; // insert endpoint that was given to you by TestTaskSDK client's manager
$token = '...'; // insert token that was given to you by TestTaskSDK client's manager
$message = 'Hi all! Hot vacancy! Beter is hiring developers!';

$sdkClient = new TTClient($endpoint, $token);
$authKey = $sdkClient->auth();

// now you may create a job!

$jobId = $sdkClient->createJob($authKey, $message);
```

After that, the job is created and you can check job's status by calling `checkJobStatus` method:

```
$status = $sdkClient->checkJobStatus($authKey, $jobId);

if ($status === TTClient::JOB_STATUS_DONE) {
    echo "Success!!";
}
```

## Methods

### __construct($endpoint, $token)

Setup endpoint and token, cap!

### setTimeout($timeout)

Call this method prior to dispatching of the job. This will limit execution time for `auth`, `createJob`,
`checkJobStatus` methods. Reusable, so set it up once.

### auth()

Returns authKey that's mandatory for the next SDK calls like `createJob`, `checkJobStatus`.

### createJob($authKey, $message)

Created a job. Job is a task to send message. Message may be sent only after some time, so this method returns
immediately. Method returns `jobId` if jow was successfully queued for dispatching. Method doesn't guaranty that
`jobId` will be dispatched. To check status of the job use `checkJobStatus` method.

If TestTaskSDK service is unavailable, exception will be thrown.

### checkJobStatus($authKey, $jobId)

Returns status of the job (specified by `jobId`). Status is one of the following hardcoded constants:
* `TTClient::JOB_STATUS_DONE` - when the message was successfully dispatched and money was charged;
* `TTClient::JOB_STATUS_IN_PROGRESS` - when the message is still in the queue;
* `TTClient::JOB_STATUS_FAILED` - when message wasn't delivered, so, your balance will not be charged.

Method may throw exception if something went wrong.
