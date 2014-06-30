<?php

class CarbineException {

  public static function Error($message) {
    throw new Exception($message);
  }

}
