<?php

namespace TehranCode\YahooApiBundle\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('TehranCodeYahooApiBundle:Default:index.html.twig', array('name' => $name));
    }

	/**
	 *
	 */
    public function yahoorequestfunAction(Request $request)
    {
        $YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
		$callback = 'http://localhost/shikbook/web/app_dev.php/yahoo_Response_route';
		# Fetch request token
		$request_token = $YahooService->getRequestToken($callback);

		//var_dump($request_token);
		# Redirect user to authorization url
		$redirect_url  = $YahooService->getAuthorizationUrl($request_token);

		$session = $request->getSession();
		$session->set('request_token_key', $request_token->key);
		$session->set('request_token_secret', $request_token->secret);

		$OpenIDUrl = $YahooService->getOpenIDUrl($callback.'?close=true');
		
		//$response = new Response('<body><a href="'.$redirect_url.'">Requets Contacts</a></body>');
        //$response->headers->setCookie(new Cookie('request_token_key', $request_token->key, time() + (60 * 5)));
        //$response->headers->setCookie(new Cookie('request_token_secret', $request_token->secret, time() + (60 * 5))); 
        //return $response; 
		//$OpenIDUrl = $YahooService->getOpenIDUrl($callback);
		//return $this->redirect($redirect_url, 301);
		return new Response('
			<body>
			<a href="'.$redirect_url.'">Requet</a>
			<a href="'.$OpenIDUrl.'">OpenIDUrl</a>
			</body>'
		);
		
		//$client = $happyrService->getGoogleClient();
		//$client->setScopes('https://www.google.com/m8/feeds');
		//$googleImportUrl = $client->createAuthUrl();
		//return new Response('<body><a href="'.$googleImportUrl.'">Requets Contacts</a></body>');
    }

	/**
	 *
	 */
    public function yahooresponsefunAction(Request $request)
    {
		if(false)
		{
			$YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');

			//$cookies = $request->cookies->all();
			//$request_token = new \OAuthToken($cookies['request_token_key'], $cookies['request_token_secret']);
			
			$session = $request->getSession();
			$request_token = new \OAuthToken($session->get('request_token_key'), $session->get('request_token_secret'));

			# Exchange request token for authorized access token
			$access_token  = $YahooService->getAccessToken($request_token, $_REQUEST['oauth_verifier']);

			# update access token
			$YahooService->setAccessToken($access_token);

			# fetch user profile
			$Contacts = $YahooService->getContacts();
			
			var_dump($Contacts);
			//var_dump($profile);
			return new Response('<body><h1>Response Page</h1></body>');
		}
		if(isset($_REQUEST['openid_mode']))
		{
		  switch($_REQUEST['openid_mode'])
		  {
			case 'discover':
			case 'checkid_setup':
			case 'checkid_immediate':
			  // handle yahoo simpleauth popup + redirect to yahoo! open id with open app oauth request
			  //header('Location: '.$oauthapp->getOpenIDUrl(isset($_REQUEST['popup']) ? $oauthapp->callback_url.'?close=true': $oauthapp->callback_url)); exit;
			break;
			case 'id_res':
				// validate claimed open id
				// extract approved request token from open id response
				echo $_REQUEST['openid_oauth_request_token'];
				//$request_token = new YahooOAuthRequestToken($_REQUEST['openid_oauth_request_token'], '');
				//$_SESSION['yahoo_oauth_request_token'] = $request_token->to_string();
				// exchange request token for access token
				//$oauthapp->token = $oauthapp->getAccessToken($request_token);
				// store access token for later
				//$_SESSION['yahoo_oauth_access_token'] = $oauthapp->token->to_string();
			break;
			case 'cancel':
			  //unset($_SESSION['yahoo_oauth_access_token']);
			  //unset($_REQUEST['openid_mode']);
			  //header('Location: '.$oauthapp->callback_url); exit;
			  // openid cancelled
			break;
			case 'associate':
			  // openid associate user
			break;
			default:
		  }
		}
		/*
		$YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
		$client = $happyrService->getGoogleClient();
		$client->authenticate($_GET['code']);
		$access_token = $client->getAccessToken();

		$response =  json_decode($access_token);
		$accesstoken = $response->access_token;
		$url = 'https://www.google.com/m8/feeds/contacts/default/full?max-results=1000&alt=json&v=3.0&oauth_token='.$accesstoken;
		$xmlresponse = $this->curl($url);
		var_dump($xmlresponse);*/
		
		return new Response('<body><h1>Response!</h1></body>');
    }
}

/*
//Working code on server for last two functions

    public function yahoorequestfunAction(Request $request)
    {
        $YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
		$callback = 'http://tehrancode.byethost7.com/web/app_dev.php/yahoo_Response_route';
		$OpenIDUrl = $YahooService->getOpenIDUrl($callback.'?close=true');
		return new Response('
			<body>
			<a href="'.$OpenIDUrl.'">OpenIDUrl</a>
			</body>'
		);
    }

    public function yahooresponsefunAction(Request $request)
    {
		if(isset($_REQUEST['openid_mode']))
		{
		 $YahooService = $this->get('TehranCode.Yahoo.OAuth.Application');
		  switch($_REQUEST['openid_mode'])
		  {
			case 'discover':
			case 'checkid_setup':
			case 'checkid_immediate':
			  // handle yahoo simpleauth popup + redirect to yahoo! open id with open app oauth request
			  //header('Location: '.$oauthapp->getOpenIDUrl(isset($_REQUEST['popup']) ? $oauthapp->callback_url.'?close=true': $oauthapp->callback_url)); exit;
			break;
			case 'id_res':
				// validate claimed open id
				// extract approved request token from open id response
				$request_token = new \YahooOAuthRequestToken($_REQUEST['openid_oauth_request_token'], '');
				// exchange request token for access token
				$access_token = $YahooService->getAccessToken($request_token);
				$YahooService->setAccessToken($access_token);
				$Contacts = $YahooService->getContacts();
				$Profile = $YahooService->getProfile();
				var_dump($Contacts);
				var_dump($Profile);
			break;
			case 'cancel':
			  //unset($_SESSION['yahoo_oauth_access_token']);
			  //unset($_REQUEST['openid_mode']);
			  //header('Location: '.$oauthapp->callback_url); exit;
			  // openid cancelled
			break;
			case 'associate':
			  // openid associate user
			break;
			default:
		  }
		}		
		return new Response('<body><h1>Response!</h1></body>');
    }
*/
