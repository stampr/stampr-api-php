<?php

abstract class StamprModel
{
  protected $id = null;
	protected $parent = null;

	public function __construct($parent)
	{
		$this->parent = $parent;
	}

  public function getId()
  {
    return $this->id;
  }

  public function client()
  {
    return $this->parent->getClient();
  }

  public function set($key, $val)
  {
    $this->$key = $val;
    return $this;
  }

  protected function importFromResource($uri)
  {
    $data = $this->client()->get($uri)->send()->json();
    if (count($data) != 1) throw new Exception(get_class($this).' Not Found');

    $json = $data[0];

    $typeName = $this::Type;
    $typeId = $typeName . '_id';

    foreach ($json as $k => $v)
    {
      $prop = $k == $typeId ? 'id' : $k; // translate ID key
      if (property_exists($this, $prop)) $this->$prop = $v;
      // TODO: debug mode, emit warning
    }

    return $this;
  }

  abstract function create();
  abstract function find();
  abstract function findAll();
  abstract function update();
  abstract function delete();
}


class StamprModelTypes
{
  const Batch = 'batch';
  const Config = 'config';
  const Mailing = 'mailing';
}
