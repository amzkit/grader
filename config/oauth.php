<?php

return [

    'authorize_url'     =>  env('MJU_OAUTH_AUTHORIZE_PATH', 'https://oauth.digital.mju.ac.th/oauth/authorize'),
    'request_token_url' =>  env('MJU_OAUTH_REQUEST_TOKEN_PATH','https://oauth.digital.mju.ac.th/oauth/token'),
    'request_user_url'  =>  env('MJU_OAUTH_REQUEST_USER_PATH','https://oauth.digital.mju.ac.th/api/user'),

    'callback_redirect_url'      =>  env('MJU_CALLBACK_URL','http://grader.local/login/mju/callback'),
    'client_id'         =>  env('MJU_CLIENT_ID'),
    'client_secret'     =>  env('MJU_CLIENT_SECRET'),

];
