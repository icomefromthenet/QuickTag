<?php
namespace QuickTag\Tests;

use DateTime;
use QuickTag\Model\TagGateway;
use QuickTag\Model\TagQuery;
use QuickTag\Model\TagBuilder;
use QuickTag\Tests\TestsWithFixture;


class QueryTest extends  TestsWithFixture
{
    
    /**
      *  Fetches a new insance of the gateway
      *
      *  @return QuickTag\Model\TagGateway
      */   
    protected function getTableGateway()
    {
        $doctrine   = $this->getDoctrineConnection();   
        $metadata   = $this->getTableMetaData(); 
        $table_name = 'quicktag_tags';
        $builder    = new TagBuilder();
        $mock_event = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcherInterface');      
        
        return new TagGateway($table_name,$doctrine,$mock_event,$metadata,null,$builder);
        
    }
    
    public function testGatewayHandsBackQuery()
    {
        $gateway = $this->getTableGateway();
        $query = $gateway->newQueryBuilder();
        $this->assertInstanceOf('QuickTag\Model\TagQuery',$query);
    }
    
        
    public function testFilterByID()
    {
        $gateway = $this->getTableGateway();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterById(1);
                
        $this->assertRegExp('/WHERE tag_id = :tag_id/',$query->getSql());
        $this->assertEquals(1,$query->getParameter('tag_id'));
    }
    
    
    public function testFilterByUserContext()
    {
        $gateway = $this->getTableGateway();
        $context = 2;
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterByUserContext($context);
        
        $this->assertRegExp('/WHERE tag_user_context = :tag_user_context/',$query->getSql());
        $this->assertEquals($context,$query->getParameter('tag_user_context'));
        
    }
    
    
    public function testFilterByNameEqual()
    {
        $gateway = $this->getTableGateway();
        $name = 'xxxxddd';
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterByNameEqual($name);
                    
        $this->assertRegExp('/WHERE tag_title = :tag_title/',$query->getSql());
        $this->assertEquals($name,$query->getParameter('tag_title'));
        
    }
    
    
    public function testFilterByNameStartsWith()
    {
        $gateway = $this->getTableGateway();
        $name = 'xxxxddd';
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterByNameStartsWith($name);
                    
        $this->assertRegExp('/WHERE tag_title LIKE :tag_title_start/',$query->getSql());
        $this->assertEquals($name.'%',$query->getParameter('tag_title_start'));
        
    }
    
    
    
    public function testFilterCreatedBefore()
    {
        $gateway = $this->getTableGateway();
        $time = new DateTime();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterCreatedBefore($time);
                    
        $this->assertRegExp('/WHERE tag_date_created <= :dte_created_before/',$query->getSql());
        $this->assertEquals($time,$query->getParameter('dte_created_before'));
        
    }
    
    
    public function testFilterCreatedAfter()
    {
        $gateway = $this->getTableGateway();
        $time = new DateTime();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterCreatedAfter($time);
                    
        $this->assertRegExp('/WHERE tag_date_created >= :dte_created_after/',$query->getSql());
        $this->assertEquals($time,$query->getParameter('dte_created_after'));
    }
    
    
    public function testFilterCreatedRange()
    {
        
        $gateway = $this->getTableGateway();
        $timeA = new DateTime();
        $timeB = new DateTime();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->filterCreatedAfter($timeA)
                    ->filterCreatedBefore($timeB);
                    
        $this->assertRegExp('/WHERE \(tag_date_created >= :dte_created_after\) AND \(tag_date_created <= :dte_created_before\)/',$query->getSql());
        $this->assertEquals($timeA,$query->getParameter('dte_created_after'));
        $this->assertEquals($timeB,$query->getParameter('dte_created_before'));
    }
    
    
    
    public function testOrderByWeight()
    {
        $gateway = $this->getTableGateway();
        $dir = 'DESC';
        
        $query = $gateway->selectQuery()
                ->start()
                    ->orderByWeight($dir);
                
        $this->assertRegExp('/ORDER BY tag_weight DESC/',$query->getSql());
    }
    
    
    
    public function testOrderByTitle()
    {
        $gateway = $this->getTableGateway();
        $dir = 'DESC';
        
        $query = $gateway->selectQuery()
                ->start()
                    ->orderByTitle($dir);
                
        $this->assertRegExp('/ORDER BY tag_title DESC/',$query->getSql());
        
    }
    
    
    
    public function testOrderByCreated()
    {
        $gateway = $this->getTableGateway();
        $dir = 'DESC';
        
        $query = $gateway->selectQuery()
                ->start()
                    ->orderByCreated($dir);
                
        $this->assertRegExp('/ORDER BY tag_date_created DESC/',$query->getSql());
        
    }
    
    
    public function testGroupByUserContext()
    {
        $gateway = $this->getTableGateway();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->groupByUserContext();
                
        $this->assertRegExp('/GROUP BY tag_user_context/',$query->getSql());
    }
    
    
    public function testGroupByTitle()
    {
        $gateway = $this->getTableGateway();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->groupByTitle();
                
        $this->assertRegExp('/GROUP BY tag_title/',$query->getSql());
        
    }
    
    
    public function testCalculateTagCount()
    {
       $gateway = $this->getTableGateway();
        
        $query = $gateway->selectQuery()
                ->start()
                    ->groupByUserContext()
                    ->calculateTagCount();
        
        $this->assertRegExp('/COUNT\(tag_id\) AS tag_count/',$query->getSql());
        $this->assertRegExp('/GROUP BY tag_user_context/',$query->getSql());
    }
    
    
}
/* End of File */