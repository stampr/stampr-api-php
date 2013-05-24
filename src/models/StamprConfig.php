<?php

require_once 'StamprModel.php';

class StamprConfig extends StamprModel
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

  public function create()
  {

  }

  public function find($id=null)
  {
    if (is_null($id)) $id = $this->id;
    $this->importFromResource('configs/'.$id);
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
