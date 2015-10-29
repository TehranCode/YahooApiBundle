# YahooApiBundle
Symfony2 Yahoo OAuth + OpenID This Bundle is a wrapper around [alexandreeffetb/yos-social-php5](https://github.com/alexandreeffetb/yos-social-php5)
# Progress Sheet
This is under construction so Don't use it or at least Debug it first!
Yahoo does answer OAuth requests from Localhost but It doesn't answer OAuth+OpenID requests

[alexandreeffetb/yos-social-php5](https://github.com/alexandreeffetb/yos-social-php5) uses:
```php
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
```
in the `alexandreeffetb\yos-social-php5\lib\Yahoo\YahooCurl.php` line 112 and some of shared servers have problem with this!so you should comment it probably or ...
# Requirements
[alexandreeffetb/yos-social-php5](https://github.com/alexandreeffetb/yos-social-php5)
# Installation
### Use Composer to get the repository
first go to [alexandreeffetb/yos-social-php5](https://github.com/alexandreeffetb/yos-social-php5) and install it!
```
$ composer require TehranCode/YahooApiBundle
```

### Setting up the bundle
A) Enable the bundles in the kernel:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new TehranCode\YahooApiBundle\TehranCodeYahooApiBundle(),
    );
}
```

B) Configure the TehranCodeYahooApiBundle

```php
// app/config/config.yml

tehran_code_yahoo_api:
    application_id:       %Your_Application_ID%
    consumer_key:         %Your_Application_consumer_key%
    consumer_secret:      %Your_Application_consumer_secret%
    callback_url:         %Your_Application_callback_url%
```
# How to Use Bundle
### OAuth sample for profile and contacts
use the 'TehranCode.Yahoo.OAuth.Application' service to make the URL
```php
	$YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
	$callback = %Your_Application_callback_url%;
	# Fetch request token
	$request_token = $YahooService->getRequestToken($callback);

	$session = $request->getSession();
	$session->set('request_token_key', $request_token->key);
	$session->set('request_token_secret', $request_token->secret);
	
	# Redirect user to authorization URL
	$redirect_url  = $YahooService->getAuthorizationUrl($request_token);
```
when user click above URL goes to yahoo and callback to route function:
```php
	$YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
	
	$session = $request->getSession();
	$request_token = new \OAuthToken($session->get('request_token_key'), $session->get('request_token_secret'));

	# Exchange request token for authorized access token
	$access_token  = $YahooService->getAccessToken($request_token, $_REQUEST['oauth_verifier']);

	# update access token
	$YahooService->setAccessToken($access_token);

	# fetch user profile
	$Profile = $YahooService->getProfile();
	# fetch user Contacts
	$Contacts = $YahooService->getContacts();
	
	var_dump($Profile);
	var_dump($Contacts);
```
### OAuth + OpenID sample for profile and contacts
yahoo does not return user email with simple OAuth and you need to use OAuth + OpenID to get it
use the 'TehranCode.Yahoo.OAuth.Application' service to make the URL
```php
	$YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
	$callback = %Your_Application_callback_url%;
	$OpenIDUrl = $YahooService->getOpenIDUrl($callback);
```
callback route function:
```php
	if(isset($_REQUEST['openid_mode']))
	{
		$YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
		if($_REQUEST['openid_mode'] == 'id_res')
		{
			// validate claimed open id
			// extract approved request token from open id response
			$request_token = new \YahooOAuthRequestToken($_REQUEST['openid_oauth_request_token'], '');
			// exchange request token for access token
			$access_token = $YahooService->getAccessToken($request_token);
			$YahooService->setAccessToken($access_token);
			$Contacts = $YahooService->getContacts();
			$Profile = $YahooService->getProfile();
			//You can access OpenID response
			var_dump($_REQUEST['openid_ax_value_email']);
			var_dump($_REQUEST['openid_ax_value_language']);
			//You can also access OAuth response
			var_dump($Contacts);
			var_dump($Profile);
			//...
		}
	}
```