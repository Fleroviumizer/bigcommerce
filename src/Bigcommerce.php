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

    protected $redirect_uri;
    protected $grant_type = 'authorization_code';
    protected $login_uri = 'https://login.bigcommerce.com';
    protected $code;
    protected $scope;
    protected $context;

    protected $auth_response = null;

    public function __call($name, $arguments)
    {
        try {
            return call_user_func_array([BigcommerceClient::class, $name], $arguments);
        }catch(Exception $e){
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    public function authenticate() : bool 
    {
        $payload = $this->payload();

        $response = Http::post($this->login_uri . '/oauth2/token', $payload);

        if ($response->successful()) {

            $response_data = $response->json();

            $this->access_token = $response_data['access_token'];

            $array_store_hash = explode('/', $response_data['context']);

            $this->store_hash = $array_store_hash[1];

            $this->setAuthenticationResponse([
                'store_hash' => $this->store_hash,
                'access_token' => $this->access_token,
                'email' => $response_data['user']['email']
            ]);

            return true;
        }

        return false;

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

    public function setAuthenticationResponse($auth_response = [])
    {
        $this->auth_response = $auth_response;
    }

    public function getAuthenticationResponse()
    {
        if (!is_null($this->auth_response) && !empty($this->auth_response))
            return $this->auth_response;

        return [];
    }

    /*
     * Set store hash;
     */
    public function setStoreHash($store_hash)
    {
        $this->store_hash = $store_hash;
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

    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }

}
