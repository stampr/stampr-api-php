<?php

require_once 'StamprModel.php';

class StamprBatch extends StamprModel
{
  const Type = StamprModelTypes::Batch;

  protected $config_id = null;
  protected $template = null;
  protected $status = StamprBatchStatus::Processing;

  public function getConfigId()
  {
    return $this->config_id;
  }

  public function getTemplate()
  {
    return $this->template;
  }

  public function getStatus()
  {
    return $this->status;
  }

  public function create()
  {

  }

  public function find($id=null)
  {
    if (is_null($id)) $id = $this->id;
    $this->importFromResource('batches/'.$id);
    return $this;
  }

  public function findAll()
  {

    return $this;
  }

  public function update()
  {

    return $this;
  }

  public function delete()
  {

    return $this;
  }
}

class StamprBatchStatus
{
  const Processing = 'processing';
  const Hold = 'hold';
}
