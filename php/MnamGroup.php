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
class MnamGroup
{
  private static $_MnamCass;
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
    self::_Init();

    //self::$_MnamCass->getCassandra()->createStandardColumnFamily($keyspace, $groupName, $columns);

    self::$_MnamCass->getCassandra()->createSuperColumnFamily(
        self::$_MnamCass->getKeyspaceName(),
        'cities',
        array(
            array(
                'name' => 'population',
                'type' => Cassandra::TYPE_INTEGER
            ),
            array(
                'name' => 'comment',
                'type' => Cassandra::TYPE_UTF8
            )
        ),
        // see the definition for these additional optional parameters
        Cassandra::TYPE_UTF8,
        Cassandra::TYPE_UTF8,
        Cassandra::TYPE_UTF8,
        'Capitals supercolumn test',
        1000,
        1000,
        0.5
    );
  }

  /**
   * Init a working Mnam instance
   */
  private static function _Init()
  {
    if(!self::$_MnamCass) self::$_MnamCass = new MnamCass();
  }
}

$obj = new MnamGroup('test');
$obj->commit();