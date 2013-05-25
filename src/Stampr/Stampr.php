<?php
require_once 'Client/StamprClient.php';
require_once 'Helpers/StamprUtils.php';
require_once 'Models/StamprAbstractModel.php';
require_once 'Models/StamprBatch.php';
require_once 'Models/StamprConfig.php';
require_once 'Models/StamprMailing.php';

class Stampr
{
  protected $client = null;

  private $quicksend_config_id = null;
  private $quicksend_batch_id = null;

  public function __construct($user, $password, $endpoint=null)
  {
    $this->client = StamprUtils::Client($user, $password, $endpoint);
  }

  public function getClient()
  {
    return $this->client;
  }

  public function factory($type, $id=null)
  {
    $targ = 'Stampr'.$type;
    $obj = new $targ($this);
    if (!is_null($id)) $obj->find($id);
    return $obj;
  }

  public function findAll($type, $status=null, $start=null, $end=null, $page=0)
  {
    return $this->factory($type)->findAll($status, $start, $end, $page);
  }

  public function mailings($status=null, $start=null, $end=null, $page=0)
  {
    return $this->findAll('Mailing', $status, $start, $end, $page);
  }

  public function batches($status=null, $start=null, $end=null, $page=0)
  {
    return $this->findAll('Batch', $status, $start, $end, $page);
  }

  public function configs($status=null, $start=null, $end=null, $page=0)
  {
    return $this->findAll('Config', $status, $start, $end, $page);
  }

  protected function getQuicksendBatchId()
  {
    if (is_null($this->quicksend_config_id))
    {
      $this->quicksend_config_id = $this->factory('Config')
                                    ->create()->getId();
    }

    if (is_null($this->quicksend_batch_id))
    {
      $this->quicksend_batch_id = $this->factory('Batch')
                                      ->set('config_id', $this->quicksend_config_id)
                                    ->create()->getId();
    }

    return $this->quicksend_batch_id;
  }

  public function mail($to, $from, $message, $batch_id=null)
  {
    if (is_null($batch_id)) $batch_id = $this->getQuicksendBatchId();

    return $this->factory('Mailing')
              ->set('batch_id', $batch_id)
              ->set('address', $to)
              ->set('returnaddress', $from)
              ->set('format', StamprMailFormat::HTML)
              ->set('data', $message)
            ->create();
  }

  public function ping()
  {
    return $this->getClient()->get('test/ping')->send()->json();
  }

  public function apiHealth()
  {
    return $this->getClient()->get('health')->send()->json();
  }
}
