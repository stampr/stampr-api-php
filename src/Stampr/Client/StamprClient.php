<?php

use Guzzle\Http\Client;
use Guzzle\Plugin\CurlAuth\CurlAuthPlugin;

require_once __DIR__ . '/../GuzzlePlugin/Aws4SignatureAuthplugin.php';

class StamprClient extends Client
{
  public function __construct($user, $password, $endpoint=null)
  {
    if (is_null($endpoint)) $endpoint = 'https://stam.pr/api';

    parent::__construct($endpoint);

    if (false === strpos('@', $user)) { // hmac
      $authPlugin = new Aws4SignatureAuthplugin($user, $password, 'us-1', 'stampr');
    }
    else {
      $authPlugin = new CurlAuthPlugin($user, $password);
    }

    $this->addSubscriber($authPlugin);
  }
}
