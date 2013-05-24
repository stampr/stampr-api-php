<?php

require_once 'models/StamprBatch.php';
require_once 'models/StamprConfig.php';
require_once 'models/StamprMailing.php';

class Stampr
{
  protected $client = null;

  public function __construct($client)
  {
    $this->client = $client;
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
