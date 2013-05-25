<?php

class StamprConfig extends StamprAbstractModel
{
  const Type = StamprModelTypes::Config;

  protected $size = StamprConfigSize::Standard;
  protected $turnaround = StamprConfigTurnaround::ThreeDay;
  protected $style = StamprConfigStyle::Color;
  protected $output = StamprConfigOutput::Simplex;
  protected $returnenvelope = false;

  public function getSize()
  {
    return $this->size;
  }

  public function getTurnaround()
  {
    return $this->turnaround;
  }

  public function getStyle()
  {
    return $this->style;
  }

  public function getOutput()
  {
    return $this->output;
  }

  public function getReturnEnvelope()
  {
    return $this->returnenvelope;
  }

  public function verify()
  {
    // No verification needed except maybe ensuring settings are real, but meh, we'll leave that up to the user
  }

  public function create()
  {
    if (!is_null($this->getId())) throw new Exception('Stampr: Unable to create, already exists');

    $this->verify();

    $this->saveToResource('configs', array(
        'size' => $this->getSize(),
        'turnaround' => $this->getTurnaround(),
        'style' => $this->getStyle(),
        'output' => $this->getOutput(),
        'returnenvelope' => $this->getReturnEnvelope(),
      ));

    return $this;
  }

  public function find($id=null)
  {
    if (is_null($id)) $id = $this->id;
    $this->importFromResource('configs/'.$id);
    return $this;
  }

  public function findAll($page=0)
  {
    if (is_null($page)) $page = 0;
    
    $uri = 'configs/browse/all/'.$page;

    $results = array();

    $data = $this->client()->get($uri)->send()->json();
    
    foreach ($data as $json)
    {
      $obj = $this->parent->factory($this::Type);
      $obj->import($json);
      $results[] = $obj;
    }

    return $results;
  }
}


class StamprConfigSize
{
  const Standard = 'standard';
  const Postcard = 'postcard';
  const Legal = 'legal';
}

class StamprConfigTurnaround
{
  const Weekend = 'weekend';
  const Overnight = 'overnight';
  const ThreeDay = 'threeday';
  const Week = 'week';
}

class StamprConfigStyle
{
  const Color = 'color';
  const Mono = 'mono';
}

class StamprConfigOutput
{
  const Simplex = 'single';
  const Duplex = 'double';
}
