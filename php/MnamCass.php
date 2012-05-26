<?php
/**
 * 
 * Mnam: Messaging, not a mess!
 * PHP library port
 * 
 * @author  jeffgirard
 * 
 */
class MnamCass{

  private $_cassInst;
  private $_configFilePath;
  private $_config = array();

  public function __construct($configFilePath=null)
  {
    $this->_configFilePath = $configFilePath;

    $this->_prepare();
    $this->_connect();
  }

  public function getCassandra()
  {
    return $this->_cassInst;
  }

  private function _prepare()
  {
    # TODO: Yaml config loading
    $this->_config = array(
        'cassandra' => array(
          'host' => '127.0.0.1',
          'port' => 9160,
          'use_framed_transport' => true,
          'send_timeout_ms' => 1000,
          'receive_timeout_ms' => 1000),

        'default' => array(
            'keyspace' => 'mnam'),

        'php' => array(
            'client_include' => 'cass-php-client/Cassandra.php')
      );
  }

  private function _connect()
  {
    $cassConf = $this->_config['cassandra'];
    if(!$cassConf) throw new Exception('Missing cassandra config');

    require_once($this->_config['php']['client_include']);

    $this->_cassInst = Cassandra::createInstance(array(
      array(
        'host' => $cassConf['host'],
        'port' => $cassConf['port'],
        'use-framed-transport' => $cassConf['use_framed_transport'],
        'send-timeout-ms' => $cassConf['send_timeout_ms'],
        'receive-timeout-ms' => $cassConf['receive_timeout_ms']
      )
    ));

    $this->_cassInst->useKeyspace($this->_config['default']['keyspace']);
    $this->_cassInst->setMaxCallRetries(5);
  }

  /**
   * Prepare fallback storage configuration, ready to serve if required!
   */
  /*private function _LoadAndConnectToFallback()
  {
    $fallbackConf = $this->_config['fallback'];
    if($fallbackConf){

      switch ($fallbackConf['engine']) {
        case 'mysql':
          throw new Exception("Fallback engine mysql connection not yet implemented");
          break;
        
        default:
          throw new Exception("Fallback engine unrecognized");
      }

    }
  }*/
}