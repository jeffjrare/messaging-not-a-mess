<?php
require 'MnamCass.php';
/**
 * 
 * Mnam: Messaging, not a mess!
 * PHP library port
 * 
 * Static class to send message into specific group, and get it written into Cassandra
 * 
 * @author  jeffgirard
 * 
 */
class Mnam
{
  private static $_MnamCass;

  public static function ForceMnamCass($mnamCass)
  {
    self::$_MnamCass = $mnamCass;
  }

  /**
   * Will create Cassandra required columnFamily required for a group
   * @param string $groupName
   * @param Array  $columns
   */
  public static function InitGroup($keyspace, $groupName, Array $columns)
  {
    self::_Init();

    self::$_MnamCass->getCassandra()->createStandardColumnFamily($keyspace, $groupName, $columns);
  }

  /**
   * Write a message into group through matching Cassandra columnFamily
   * @param string $groupName is the message group name
   * @param string|array $key is unique in the message group name
   * @param Array  $fields
   * @param datetime $occuredAt
   */
  public static function Write($groupName, $key, Array $fields, $occuredAt)
  {
    self::_Init();

    if(is_array($key)) $key = implode('.', $key);

//    if(self::$_MnamCass->getCassandra()->getConnection()->isOpen()){
      self::$_MnamCass->getCassandra()->set("{$groupName}.{$key}", $fields);

  //  }else{
    //  throw new Exception("No fallback implemented yet when cassandra isnt open");
  //  }
  }

  /**
   * Write many message into group through matching Cassandra columnFamily
   * @param string $groupName 
   * @param Array  $columns   
   */
  public static function WriteMany($groupName, Array $columns)
  {
    foreach($columns as $key => $fields){
      self::Write($groupName, $key, $fields);
    }
  }

  /**
   * Fetch message from group by it unique key
   * @param string $groupName 
   * @param string $key       
   */
  public static function Read($groupName, $key)
  {
    self::_Init();

    if(is_array($key)) $key = implode('.', $key);

    return self::$_MnamCass->getCassandra()->get("{$groupName}.{$key}");
  }

  /**
   * Init a working Mnam instance
   */
  private static function _Init()
  {
    if(!self::$_MnamCass) self::$_MnamCass = new MnamCass();
  }
}

//require_once('cass-php-client/Cassandra.php');

/*Mnam::InitGroup('login',
  array(
    array(
      'name' => 'name',
      'type' => Cassandra::TYPE_UTF8,
      'index-type' => Cassandra::INDEX_KEYS, // create secondary index
      'index-name' => 'NameIdx'
    )));*/

/*Mnam::InitGroup('sold',
  array(
    array(
      'name' => 'val1',
      'type' => Cassandra::TYPE_UTF8,
      'index-type' => Cassandra::INDEX_KEYS, // create secondary index
      'index-name' => 'val1Idx'
    ),
    array(
      'name' => 'val2',
      'type' => Cassandra::TYPE_UTF8,
      'index-type' => Cassandra::INDEX_KEYS, // create secondary index
      'index-name' => 'val2Idx'
    )));*/

Mnam::Write('login', 'jeff', array('name' => 'Jeff'));
Mnam::Write('sold', 'jeff', array('val1' => '1', 'val2' => '2'));

for($i=1;$i<=2000;$i++){
  Mnam::Write('sold', "jeff{$i}", array('val1' => '1', 'val2' => '2'));
}
