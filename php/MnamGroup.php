<?php
require 'MnamCass.php';
/**
 * 
 * Mnam: Messaging, not a mess!
 * PHP library port
 * 
 * Class to create/manage message groups
 * 
 * @author  jeffgirard
 * 
 */
class MnamGroup{

  private $_name;
  private $_mnamFields=array();

  public function __construct($groupName, $fieldNames=null)
  {
    $this->_name = $groupName;

    if($fieldNames && is_array($fieldNames)){
      foreach ($fieldNames as $field) $this->addField($field);
    }
  }

  public function addField($fieldName)
  {
    $this->_mnamFields[] = array(
      'name' => $fieldName,
      'type' => Cassandra::TYPE_UTF8);
  }

  public function commit()
  {
    
  }
}