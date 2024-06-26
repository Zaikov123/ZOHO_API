<?php

namespace App\Services;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ZohoService
{
    protected $client;
    protected $refreshToken;
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->refreshToken = env('ZOHO_REFRESH_TOKEN');
        $this->clientId = env('ZOHO_CLIENT_ID');
        $this->clientSecret = env('ZOHO_CLIENT_SECRET');
    }

    private function getAccessToken()
    {
        if (!$this->accessToken || $this->isAccessTokenInvalid()) {
            try {
                $response = $this->client->post('https://accounts.zoho.eu/oauth/v2/token', [
                    'form_params' => [
                        'refresh_token' => $this->refreshToken,
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => 'refresh_token',
                        'scope' => 'ZohoCRM.modules.all,ZohoCRM.settings.all',
                    ],
                ]);

                $data = json_decode($response->getBody(), true);

                if (isset($data['access_token'])) {
                    $this->accessToken = $data['access_token'];

                    $this->saveAccessToken($this->accessToken);

                    return $this->accessToken;
                } else {
                    throw new \Exception('Access token not found in Zoho API response');
                }
            } catch (\Exception $e) {
                throw new \Exception('Error fetching access token: ' . $e->getMessage());
            }
        }

        return $this->accessToken;
    }

    private function isAccessTokenInvalid()
    {
       try {
            $response = $this->client->get('https://www.zohoapis.eu/crm/v2/Users', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $this->accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            return false;
        } catch (\Exception $e) {
            if ($e->getCode() === 401) {
                return true;
            }
            throw new \Exception('Error validating access token: ' . $e->getMessage());
        }
    }

    private function saveAccessToken($token)
    {
//        file_put_contents(app()->environmentFilePath(), str_replace(
//            'ZOHO_ACCESS_TOKEN=' . env('ZOHO_ACCESS_TOKEN'),
//            'ZOHO_ACCESS_TOKEN=' . $token,
//            file_get_contents(app()->environmentFilePath())
//        ));
//
//        Dotenv::createImmutable(base_path())->load();
        $this->accessToken = $token;
    }

    public function getDeals()
    {
        $accessToken = $this->getAccessToken();
        try {
            $response = $this->client->get('https://www.zohoapis.eu/crm/v2/Deals', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseBody = (string)$response->getBody();

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            logger()->error('Error fetching deals:', ['message' => $e->getMessage()]);
            throw new \Exception('Error fetching deals: ' . $e->getMessage());
        }
    }

    public function createDeal($data)
    {
        $accessToken = $this->getAccessToken();

        try {
            $response = $this->client->post('https://www.zohoapis.eu/crm/v2/Deals', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'data' => [
                        [
                            'Deal_Name' => $data['deal_name'],
                            'Stage' => $data['deal_stage'],
                            'Account_Name' => $data['account'],
                        ]
                    ],
                ]
            ]);

            $responseBody = (string)$response->getBody();

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            throw new \Exception('Error creating deal: ' . $e->getMessage());
        }
    }

    public function getAccounts()
    {
        $accessToken = $this->getAccessToken();
        try {
            $response = $this->client->get('https://www.zohoapis.eu/crm/v2/Accounts', [
                'headers' => [
                    'Authorization' => 'Zoho-oauthtoken ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseBody = (string)$response->getBody();

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            logger()->error('Error fetching deals:', ['message' => $e->getMessage()]);
            throw new \Exception('Error fetching deals: ' . $e->getMessage());
        }
    }

    public function createAccount($data)
    {
        $accessToken = $this->getAccessToken();

        $response = $this->client->post('https://www.zohoapis.eu/crm/v2/Accounts', [
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken ' . $accessToken
            ],
            'json' => [
                'data' => [
                    [
                        'Account_Name' => $data['account_name'],
                        'Website' => $data['account_website'],
                        'Phone' => $data['account_phone'],
                    ]
                ]
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
