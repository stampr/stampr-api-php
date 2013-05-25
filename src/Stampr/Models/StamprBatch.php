<?php

class StamprBatch extends StamprAbstractModel
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

  public function verify()
  {
    if (is_null($this->getConfigId())) throw new Exception('Stampr: Config ID required');
    if (is_null($this->getStatus())) throw new Exception('Stampr: Batch status required');
  }

  public function create()
  {
    if (!is_null($this->getId())) throw new Exception('Stampr: Unable to create, already exists');

    $this->verify();

    $this->saveToResource('batches', array(
        'config_id' => $this->getConfigId(),
        'template' => $this->getTemplate(),
        'status' => $this->getStatus(),
      ));

    return $this;
  }

  public function find($id=null)
  {
    if (is_null($id)) $id = $this->id;
    $this->importFromResource('batches/'.$id);
    return $this;
  }

  public function findAll($status=null, $start=null, $end=null, $page=0)
  {
    return $this->importAllFromResource('batches', $status, $start, $end, $page);
  }

  public function mailings($status=null, $start=null, $end=null, $page=0)
  {
    if (is_null($this->getId())) throw new Exception('Stampr: Batch not created, cannot find mailings');

    return $this->importAllFromResource('batches/'.$this->getId().'/mailings', $status, $start, $end, $page);
  }

  public function update()
  {
    if (is_null($this->getId())) throw new Exception('Stampr: Unable to update.  Does not exist');

    $this->verify();

    $this->saveToResource('batches/'.$this->getId(), array(
        'status' => $this->getStatus(),
      ));

    return $this;
  }

  public function delete()
  {
    if (is_null($this->getId())) throw new Exception('Stampr: Unable to delete.  Does not exist');

    $this->removeFromResource('batches/'.$this->getId());

    return $this;
  }
}

class StamprBatchStatus
{
  const Processing = 'processing';
  const Hold = 'hold';
}
