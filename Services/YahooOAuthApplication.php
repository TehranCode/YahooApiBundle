<?php

namespace TehranCode\YahooApiBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface; 

/**
 * Class YahooOAuthApplication
 *
 * This is the Yahoo OAuth Application
 */
class YahooOAuthApplication
{
    /**
     * @var \YahooOAuthApplication client
     */
    protected $client;
    
	/**
     *
     */
	public $callback;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
		$client = new \YahooOAuthApplication($config['consumer_key'], $config['consumer_secret'], $config['application_id'], $config['callback_url']);
		
        $this->client = $client;
        $this->callback = $config['callback_url'];
    }

	public function setAccessToken($accessToken)
    {
        //$this->client->setAccessToken($accessToken);
        $this->client->token = $accessToken;
    }
	
	public function getRequestToken($callback = "oob")
	{
		$result = $this->client->getRequestToken($callback);
		//var_dump("The getRequestToken result:".$result."+++");
		return $result;
		//return $this->client->getRequestToken($callback);
	}
	
	public function getAuthorizationUrl($oauth_request_token)
	{
		return $this->client->getAuthorizationUrl($oauth_request_token);
	}
	
	public function getAccessToken($oauth_request_token, $verifier = null)
	{
		return $this->client->getAccessToken($oauth_request_token, $verifier);
	}
	
	public function getProfile($guid = null)
	{
		return $this->client->getProfile($guid);
	}
	
	public function getOpenIDUrl($return_to = false, $lang = 'en', $openIdEndpoint = 'https://open.login.yahooapis.com/openid/op/auth')
	{
		return $this->client->getOpenIDUrl($return_to, $lang, $openIdEndpoint);
	}

	public function getConnections($guid = null, $offset = 0, $limit = 10)
	{
		return $this->client->getConnections($guid, $offset, $limit);
	}

	public function getContacts($guid = null, $offset = 0, $limit = 2)
	{
		return $this->client->getContacts($guid, $offset, $limit);
	}
}