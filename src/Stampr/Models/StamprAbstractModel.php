<?php

abstract class StamprAbstractModel
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
    $this->importFromJsonRepresentation($json);
  }

  protected function importAllFromResource($uri, $status=null, $start=null, $end=null, $page=0)
  {
    if (is_null($page)) $page = 0; // if page null, default to first page

    $uri .= is_null($status) ? '/browse' : '/with/'.$status;
    if (!is_null($start)) $uri .= '/'.urlencode($start);
    if (!is_null($start) && !is_null($end)) $uri .= '/'.urlencode($end);
    $uri .= '/'.$page;

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

  protected function importFromJsonRepresentation($json)
  {
    $typeName = $this::Type;
    $typeId = $typeName . '_id';

    foreach ($json as $k => $v)
    {
      $prop = $k == $typeId ? 'id' : $k; // translate ID key
      if (property_exists($this, $prop)) $this->$prop = $v;
      // TODO: debug mode, emit warning
    }
  }

  protected function saveToResource($uri, $arr)
  {
    $json = $this->client()->post($uri, null, $arr)->send()->json();
    $this->importFromJsonRepresentation($json);
  }

  protected function removeFromResource($uri)
  {
    $json = $this->client()->delete($uri)->send()->json();
    if (true !== $json) throw new Exception('Stampr: Unable to remove');
  }

  public function import($arr)
  {
    $this->importFromJsonRepresentation($arr);
    return $this;
  }

  public function save()
  {
    return is_null($this->id) ? $this->create() : $this->update();
  }

  public function create() { throw new Exception('Stampr: Unsupported action "create"'); }
  public function find($id=null) { throw new Exception('Stampr: Unsupported action "find"'); }
  public function findAll($status=null, $start=null, $end=null, $page=0) { throw new Exception('Stampr: Unsupported action "findAll"'); }
  public function update() { throw new Exception('Stampr: Unsupported action "update"'); }
  public function delete() { throw new Exception('Stampr: Unsupported action "delete"'); }

  public abstract function verify();
}


class StamprModelTypes
{
  const Batch = 'batch';
  const Config = 'config';
  const Mailing = 'mailing';
}
