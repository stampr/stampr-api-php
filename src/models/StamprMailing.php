<?php

require_once 'StamprModel.php';

class StamprMailing extends StamprModel
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

  public function create()
  {

  }

  public function find($id=null)
  {
    if (is_null($id)) $id = $this->id;
    $this->importFromResource('mailings/'.$id);

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

class StamprMailFormat
{
  const None = 'none';
  const JSON = 'json';
  const HTML = 'html';
  const PDF = 'pdf';
}
