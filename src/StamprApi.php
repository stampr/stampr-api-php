<?php
require '../vendor/autoload.php';
use Guzzle\Http\Client;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

class StamprApi extends Client
{
	public function __construct($user, $password, $endpoint='https://stam.pr/api') 
	{
		parent::__construct($endpoint);
		$authPlugin = new CurlAuthPlugin($user, $password);
		$this->addSubscriber($authPlugin);
	}
}