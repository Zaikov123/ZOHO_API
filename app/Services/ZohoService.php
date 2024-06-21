<?php

namespace App\Services;

use GuzzleHttp\Client;

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
        $this->accessToken = $this->getAccessToken();
    }

    private function getAccessToken()
    {
        try {
            $response = $this->client->post('https://accounts.zoho.eu/oauth/v2/token', [
                'form_params' => [
                    'refresh_token' => $this->refreshToken,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'grant_type' => 'refresh_token',
                    'scope' => 'ZohoCRM.modules.all,ZohoCRM.settings.all', // Пример scope для доступа ко всем модулям CRM
                ],
            ]);

            $data = json_decode($response->getBody(), true);

            // Log the entire response for debugging
            logger()->debug('Zoho API response:', $data);

            if (isset($data['access_token'])) {
                return $data['access_token'];
            } else {
                throw new \Exception('Access token not found in Zoho API response');
            }
        } catch (\Exception $e) {
            // Log or handle the error appropriately
            throw new \Exception('Error fetching access token: ' . $e->getMessage());
        }
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

            $responseBody = (string) $response->getBody();

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            logger()->error('Error fetching deals:', ['message' => $e->getMessage()]);
            throw new \Exception('Error fetching deals: ' . $e->getMessage());
        }
    }

    public function createDeal($data)
    {
        $accessToken = $this->getAccessToken();

        // Log the access token and data for debugging
        logger()->debug('Access Token:', ['access_token' => $accessToken]);
        logger()->debug('Data being sent to Zoho:', $data);

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
                            // Добавьте здесь другие необходимые поля
                        ]
                    ],
                    // убрал 'trigger' => ['approval']
                ]
            ]);

            // Log the response for debugging
            $responseBody = (string) $response->getBody();
            logger()->debug('Response from Zoho:', ['response' => $responseBody]);

            return json_decode($responseBody, true);
        } catch (\Exception $e) {
            // Log the error message for debugging
            logger()->error('Error creating deal:', ['message' => $e->getMessage()]);
            throw new \Exception('Error creating deal: ' . $e->getMessage());
        }
    }



    public function createAccount($data)
    {
        $response = $this->client->post('https://www.zohoapis.eu/crm/v2/Accounts', [
            'headers' => [
                'Authorization' => 'Zoho-oauthtoken ' . $this->accessToken,
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
