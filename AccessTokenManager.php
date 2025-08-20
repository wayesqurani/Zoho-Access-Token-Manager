<?php

class ZohoAccessTokenGenerator
{
    private $client_id;
    private $client_secret;
    private $refresh_token;
    private $grant_type;
    private $token_url;
    private $token_file;

    public function __construct($client_id, $client_secret, $refresh_token, $token_url, $grant_type = 'refresh_token')
    {
        $this->client_id     = $client_id;
        $this->client_secret = $client_secret;
        $this->refresh_token = $refresh_token;
        $this->grant_type    = $grant_type;
        $this->token_url     = $token_url;
        $this->token_file    = __DIR__ . '/access_token.json'; // Save token in same folder
    }

    /**
     * Get a valid access token (from file if still valid, or regenerate)
     */
    public function getAccessToken()
    {
        if (file_exists($this->token_file)) {
            $data = json_decode(file_get_contents($this->token_file), true);
            $created_at = $data['created_at'] ?? 0;
            $expires_in = $data['expires_in'] ?? 3600; // default 1 hour if not set
            $token_age = time() - $created_at;

            // Refresh token 5 minutes (300s) before expiration
            $buffer_time = 300;

            if ($token_age < ($expires_in - $buffer_time) && isset($data['access_token'])) {
                return $data['access_token'];
            }
        }

        // Token not found or expired, generate new one
        $newToken = $this->generateAccessToken();

        if (isset($newToken['access_token'])) {
            // Store creation time and expires_in for future checks
            $newToken['created_at'] = time();

            // Sometimes expires_in might not be returned, fallback to 3600
            if (!isset($newToken['expires_in'])) {
                $newToken['expires_in'] = 3600;
            }

            file_put_contents($this->token_file, json_encode($newToken, JSON_PRETTY_PRINT));
            return $newToken['access_token'];
        }

        return null; // Failure
    }

    /**
     * Generate a new access token using refresh token
     */
    private function generateAccessToken()
    {
        $post_fields = [
            "client_id"     => $this->client_id,
            "client_secret" => $this->client_secret,
            "refresh_token" => $this->refresh_token,
            "grant_type"    => $this->grant_type
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->token_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return [
                'error' => 'Curl error: ' . curl_error($ch)
            ];
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
