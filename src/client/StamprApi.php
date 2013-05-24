<?php
$cwd = realpath(dirname(__FILE__));
require $cwd . '/../../vendor/autoload.php';
use Guzzle\Http\Client;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

class StamprApi extends Client
{
	public function __construct($user, $password, $endpoint=null)
	{
    if (is_null($endpoint)) $endpoint = 'https://stam.pr/api';

		parent::__construct($endpoint);
		$authPlugin = new CurlAuthPlugin($user, $password);
		$this->addSubscriber($authPlugin);
	}
}
