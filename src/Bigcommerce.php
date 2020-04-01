<?php namespace Fleroviumizer\Bigcommerce;

use Fleroviumizer\Bigcommerce\Exceptions\ApiException;
use Bigcommerce\Api\Client as BigcommerceClient;
//
use Illuminate\Support\Facades\Http;
//use Bigcommerce\Api\Client as Bigcommerceclient;
//use Illuminate\Support\Facades\Storage;
//use App\Config;  //Database Connection
//use Bigcommerce\Api\Connection;

use Exception;

class Bigcommerce{

    protected $client_id;
    protected $client_secret;
    protected $store_hash;
    protected $access_token;
    protected $version = "v3";
    protected $api;
    protected $connection;
    protected $store_url;
    protected $username;
    protected $api_key;

    protected $redirect_uri;
    protected $grant_type = 'authorization_code';
    protected $login_uri = 'https://login.bigcommerce.com';
    protected $code;
    protected $scope;
    protected $context;

    public function __call($name, $arguments)
    {
        try {
            return call_user_func_array([BigcommerceClient::class, $name], $arguments);
        }catch(Exception $e){
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function authenticate()
    {
        $payload = $this->payload();

        $response = Http::post($this->login_uri . '/oauth2/token', $payload);

        if ($response->successful()) {

            $response_data = $response->json();

            $this->access_token = $response_data['access_token'];

            $array_store_hash = explode('/', $response_data['context']);

            $this->store_hash = $array_store_hash[1];

            $storage = [
                'store_hash' => $this->store_hash,
                'access_token' => $this->access_token,
                'email' => $response_data['user']['email']
            ];

            return 'App installed successfully';
        }

        return 'App installation failed. ' . $response->status() . ' : ' . $response->body();

    }



    private function payload()
    {
        return [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'grant_type' => $this->grant_type,
            'code' => $this->code,
            'scope' => $this->scope,
            'context' => $this->context,
        ];
    }

    public function setConnection($connection_name = 'oAuth')
    {

        $connections = ['oAuth'];

        if (! in_array($connection_name, $connections))
            throw new Exception('Connection not found.');

        $this->connection = $connection_name;
        $this->$connection_name();
    }

    public function verifyPeer($option = false)
    {
        $this->api->verifyPeer($option);

        return $this;
    }

    private function oAuth()
    {

        $this->api = new BigcommerceClient;

        $this->api->configure(array(
            'client_id' => $this->client_id,
            'auth_token' => $this->access_token,
            'store_hash' => $this->store_hash
        ));
    }

    /*
     * Set store hash;
     */
    public function setStoreHash($store_hash)
    {
        $this->store_hash = $store_hash;
        return $this;
    }

    public function setApiVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    public function setAccessToken($access_token)
    {
        $this->access_token = $access_token;
        return $this;
    }

    public function setClientId($client_id)
    {
        $this->client_id = $client_id;
        return $this;
    }

    public function setClientSecret($client_secret)
    {
        $this->client_secret = $client_secret;
        return $this;
    }

    public function setStoreUrl($store_url)
    {
        $this->store_url = $store_url;
        return $this;
    }

    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setApiKey($api_key)
    {
        $this->api_key = $api_key;
        return $this;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function setScope($scope)
    {
        $this->scope = $scope;
        return $this;
    }

    public function setContext($context)
    {
        $this->context = $context;
        return $this;
    }

}
