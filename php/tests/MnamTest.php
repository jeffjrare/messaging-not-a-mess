<?php
require_once dirname(__FILE__).'/../cass-php-client/Cassandra.php';
require_once dirname(__FILE__).'/../Mnam.php';
require_once dirname(__FILE__).'/../MnamCass.php';

/**
 * 
 * WORK IN PROGRESSSSS
 * 
 * @author  jeffgirard
 * 
 */
class MnamTest extends PHPUnit_Framework_TestCase
{
    private $_cassInst;
    private $_mnamCass;

    protected function setup()
    {
        $this->_cassInst = $this->getMock('Cassandra', null, array(), '', false);
        $this->_cassInst->expects($this->any())->method('getCurrentKeyspace')->will($this->returnValue(true));
        $this->_cassInst->expects($this->any())->method('set')->will($this->returnValue(true));

        $this->_mnamCass = $this->getMock('MnamCass');
        $this->_mnamCass->expects($this->any())->method('getCassandra')->will($this->returnValue($this->_cassInst));

        Mnam::ForceMnamCass($this->_mnamCass);
    }

    public function testWrite()
    {
        //Mnam::Write('login', 'jeff', array('name' => 'Jeff'));
    }

    public function testWriteMany()
    {

    }

    public function testRead()
    {

    }
}