<?php

class StamprMailing extends StamprAbstractModel
{
  const Type = StamprModelTypes::Mailing;

  protected $batch_id = null;
  protected $address = null;
  protected $returnaddress = null;
  protected $format = StamprMailFormat::None;
  protected $data = null;
  protected $md5 = null;

  public function getBatchId()
  {
    return $this->batch_id;
  }

  public function getAddress()
  {
    return $this->address;
  }

  public function getReturnAddress()
  {
    return $this->returnaddress;
  }

  public function getFormat()
  {
    return $this->format;
  }

  public function getData()
  {
    return $this->data;
  }

  protected function encodedDataForApi()
  {
    return base64_encode($this->getData());
  }

  public function verify()
  {
    if (is_null($this->getBatchId())) throw new Exception('Stampr: Batch ID required');
    if (is_null($this->getAddress())) throw new Exception('Stampr: Address required');
    if (is_null($this->getReturnAddress())) throw new Exception('Stampr: Return address required');
    if (is_null($this->getFormat())) throw new Exception('Stampr: Mail format required');
    if ($this->getFormat() != StamprMailFormat::None && is_null($this->getData())) throw new Exception('Stampr: Mail body required if mail format is not None');
  }

  public function create()
  {
    if (!is_null($this->getId())) throw new Exception('Stampr: Unable to create, already exists');

    $this->verify();

    $encodedData = $this->encodedDataForApi();
    $this->saveToResource('mailings', array(
        'batch_id' => $this->getBatchId(),
        'address' => $this->getAddress(),
        'returnaddress' => $this->getReturnAddress(),
        'format' => $this->getFormat(),
        'data' => $encodedData,
        'md5' => md5($encodedData),
      ));

    return $this;
  }

  public function find($id=null)
  {
    if (is_null($id)) $id = $this->id;
    $this->importFromResource('mailings/'.$id);

    return $this;
  }

  public function findAll($status=null, $start=null, $end=null, $page=0)
  {
    return $this->importAllFromResource('mailings', $status, $start, $end, $page);
  }

  public function delete()
  {
    if (is_null($this->getId())) throw new Exception('Stampr: Unable to delete.  Does not exist');

    $this->removeFromResource('mailings/'.$this->getId());

    return $this;
  }
}

class StamprMailFormat
{
  const None = 'none';
  const JSON = 'json';
  const HTML = 'html';
  const PDF = 'pdf';
}
