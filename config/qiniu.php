<?php

return [
    'domain' =>env('QINIU_URL', ''),
    'access_key' => env('QINIU_AK', ''), //AccessKey
    'secret_key' => env('QINIU_SK', ''), //SecretKey
    'bucket' => env('QINIU_BUCKET', 'voice'), //Bucket名字,
    'token_expires' => env('QINIU_TOKEN_EXPIRED', 1800),


];
