<?php
/**
 * Mnam: Messaging, not a mess!
 * PHP library port
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

  /**
   * Will create Cassandra required columnFamily required for a group
   * @param string $groupName
   * @param Array  $columns
   */
  public static function InitGroup($groupName, Array $columns)
  {
    self::_Init();

    self::$_MnamInst->getCassandra()->createStandardColumnFamily(self::$_MnamInst->_config['default']['keyspace'], $groupName, $columns);
  }

  /**
   * Write a message into group through matching Cassandra columnFamily
   * @param string $groupName 
   * @param string|array $key       
   * @param Array  $fields    
   */
  public static function Write($groupName, $key, Array $fields)
  {
    self::_Init();

    if(is_array($key)) $key = implode('.', $key);

    self::$_MnamInst->getCassandra()->set("{$groupName}.{$key}", $fields);
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

    return self::$_MnamInst->getCassandra()->get("{$groupName}.{$key}");
  }

  /**
   * Init a working Mnam instance
   */
  private static function _Init()
  {
    if(!self::$_MnamInst) self::$_MnamInst = new self();
  }

  /**
   * Load everything needed to work with messages
   * - Load yaml config file
   * - Call Cassandra setup and connection process
   */
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

  /**
   * Prepare Cassandra required configuration and connect to node
   */
  private function _LoadAndConnectToCass()
  {
    $cassConf = $this->_config['cassandra'];
    if(!$cassConf) throw new Exception('Missing cassandra config');

    $this->_cassandraInst = Cassandra::createInstance(array(
      array(
        'host' => $cassConf['host'],
        'port' => $cassConf['port'],
        'use-framed-transport' => $cassConf['use_framed_transport'],
        'send-timeout-ms' => $cassConf['send_timeout_ms'],
        'receive-timeout-ms' => $cassConf['receive_timeout_ms']
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
