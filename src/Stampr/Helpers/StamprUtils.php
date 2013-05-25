<?php

class StamprUtils 
{
  public static function Client($user, $password, $endpoint=null)
  {
    return new StamprClient($user, $password, $endpoint);
  }

  public static function Date($unix_timestamp)
  {
  	return gmdate('Y-m-d\TH:i:sP', $unix_timestamp);
  }

}