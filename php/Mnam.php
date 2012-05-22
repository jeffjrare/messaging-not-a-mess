<?php
/**
 * 
 * @author  jeffgirard
 * 
 */

class Mnam{

  private static $_MnamInst;
  public $_config;
  private $_cassandraInst;

  protected function __construct()
  {
    $this->_LoadConfiguration();
    $this->_LoadAndConnectToCass();
  }

  public function getCassandra()
  {
    return $this->_cassandraInst;
  }

  public static function InitGroup($groupName, Array $columns)
  {
    self::_Init();

    self::$_MnamInst->getCassandra()->createStandardColumnFamily(self::$_MnamInst->_config['default']['keyspace'], $groupName, $columns);
  }

  public static function Write($groupName, $key, Array $fields)
  {
    self::_Init();

    if(is_array($key)) $key = implode('.', $key);

    self::$_MnamInst->getCassandra()->set("{$groupName}.{$key}", $fields);
  }

  public static function WriteMany($groupName, Array $columns)
  {
    foreach($columns as $key => $fields){
      self::Write($groupName, $key, $fields);
    }
  }

  public static function Read($groupName, $key)
  {
    self::_Init();

    if(is_array($key)) $key = implode('.', $key);

    return self::$_MnamInst->getCassandra()->get("{$groupName}.{$key}");
  }

  private static function _Init()
  {
    if(!self::$_MnamInst) self::$_MnamInst = new self();
  }

  private function _LoadConfiguration()
  {
    # TODO: Yaml config loading
    $this->_config = array(
        'cassandra' => array(
          'host' => '127.0.0.1',
          'port' => 9160),

        'default' => array(
            'keyspace' => 'mnam'),

        'php' => array(
            'client_include' => 'cass-php-client/Cassandra.php')
      );

    self::_LoadAndConnectToCass();
  }

  private function _LoadAndConnectToCass()
  {
    $this->_cassandraInst = Cassandra::createInstance(array(
      array(
        'host' => $this->_config['cassandra']['host'],
        'port' => $this->_config['cassandra']['port'],
        'use-framed-transport' => true,
        'send-timeout-ms' => 1000,
        'receive-timeout-ms' => 1000
      )
    ));

    $this->_cassandraInst->useKeyspace($this->_config['default']['keyspace']);
    $this->_cassandraInst->setMaxCallRetries(5);
  }
}

require_once('cass-php-client/Cassandra.php');

/*Mnam::InitGroup('login',
  array(
    array(
      'name' => 'name',
      'type' => Cassandra::TYPE_UTF8,
      'index-type' => Cassandra::INDEX_KEYS, // create secondary index
      'index-name' => 'NameIdx'
    )));*/

Mnam::Write('login', 'jeff', array('name' => 'Jeff'));
