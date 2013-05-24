<?php

require_once 'client/StamprApi.php';
require_once 'models/StamprBatch.php';
require_once 'models/StamprConfig.php';
require_once 'models/StamprMailing.php';

class Stampr
{
  protected $client = null;

  public static function Client($user, $password, $endpoint=null)
  {
    return new StamprApi($user, $password, $endpoint);
  }

  public function __construct($user, $password, $endpoint=null)
  {
    $this->client = self::Client($user, $password, $endpoint);
  }

  public function factory($type, $id=null)
  {
    $targ = 'Stampr'.$type;
    $obj = new $targ($this);
    if (!is_null($id)) $obj->find($id);
    return $obj;
  }

  public function getClient()
  {
    return $this->client;
  }
}
