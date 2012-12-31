<?php
namespace QuickTag\Tests;

use PDO;
use PHPUnit_Extensions_Database_Operation_Composite;
use PHPUnit_Extensions_Database_TestCase;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class TestsWithFixture extends PHPUnit_Extensions_Database_TestCase
{
    
    //  ----------------------------------------------------------------------------
    
    /**
      *  @var PDO  only instantiate pdo once for test clean-up/fixture load
      *  @access private
      */ 
    static private $pdo = null;

    /**
      *  @var  \Doctrine\DBAL\Connection
      *  @access private
      */
    static private $doctrine_connection;
    
    /**
      *  @var PHPUnit_Extensions_Database_DB_IDatabaseConnection only instantiate once per test
      *  @access private
      */
    private $conn = null;
    
    
    final public function getConnection()
    {
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO($GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }

    
    protected function getSetUpOperation()
    {
        return new PHPUnit_Extensions_Database_Operation_Composite(array(
            new DBOperationSetEnv('foreign_key_checks',0),
            parent::getSetUpOperation(),
            new DBOperationSetEnv('foreign_key_checks',1),
        ));
    }
    
    
    public function getDataSet()
    {
        return  $this->createXMLDataSet(__DIR__ . DIRECTORY_SEPARATOR .'fixture.xml');
    }
    
    
    //------------------------------------------------------------------
    # Needed for testing database components 
    
    /**
    * Gets a db connection to the test database
    *
    * @access public
    * @return \Doctrine\DBAL\Connection
    */
    public function getDoctrineConnection()
    {
        if(self::$doctrine_connection === null) {
        
            $config = new \Doctrine\DBAL\Configuration();
            
            $connectionParams = array(
                'dbname' => $GLOBALS['DB_DBNAME'],
                'user' => $GLOBALS['DB_USER'],
                'password' => $GLOBALS['DB_PASSWD'],
                'host' => 'localhost',
                'driver' => 'pdo_mysql',
            );
        
           self::$doctrine_connection = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
        }
        
        return self::$doctrine_connection;
        
    }
    
    
    public function getTableMetaData()
    {
        $table = new \DBALGateway\Metadata\Table('quicktag_tags');
        
        $table->addColumn('tag_id'          ,'integer' ,array("unsigned" => true));
        $table->addColumn('tag_user_context','integer' ,array("unsigned" => true,'notnull' => false));
        $table->addColumn('tag_date_created','datetime',array('notnull' => true));
        $table->addColumn('tag_weight'      ,'float'   ,array("unsigned" => true,'notnull' => false));
        $table->addColumn('tag_title'       ,'string'  ,array('length'=> 45,'notnull' => true));
        $table->setPrimaryKey(array("tag_id"));
        $table->addIndex(array('tag_user_context'));
        
        # vcolumn for counts
        $table->addVirtualColumn('tag_count','integer',array('unsigned' => true));
        
        return $table;
        
    }
    
}
/* End of File */