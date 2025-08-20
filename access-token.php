<?php
require_once 'AccessTokenManager.php';
$zoho = new ZohoAccessTokenGenerator(
    client_id: 'YOUR_CLIENT_ID',
    client_secret: 'YOUR_CLIENT_SECRET',
    refresh_token: 'YOUR_REFRESH_TOKEN',
    token_url: 'https://accounts.zoho.com/oauth/v2/token'
);
$zohoAccessToken = $zoho->getAccessToken();
echo "Access Token: {$zohoAccessToken}\n";
?>