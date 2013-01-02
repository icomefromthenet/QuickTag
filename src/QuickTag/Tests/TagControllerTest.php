<?php
namespace QuickTag\Tests;



class TagControllerTest extends TestsWithFixture
{
    
    public function createApplication()
    {
        $_SERVER["APP_ENVIRONMENT"] = "development";
        $app = require __DIR__.'/../../../app.php';
        $app['exception_handler']->disable();
        $app['session.test'] = true;
        
        return $app;
    }
    
    
    public function testA()
    {
        $tagAPI = $this->app['qtag'];
        
        
    }
        
    public function testFetchSingleTag()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags/1');

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        $tag     = $results->result;
        
        $this->assertEquals(1,$tag->tagId);
        $this->assertEquals('rwod4',$tag->tagTitle);
        $this->assertEquals(1,$tag->tagWeight);
        $this->assertEquals(1,$tag->tagUserContext);
            
    }
    
    
    public function testFetchSingleTagMissing()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags/10000');

        # request returned 200 ok
        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode()
        );
            
    }
    
    
    public function testDeleteTag()
    {
        $client = $this->createClient();
        $crawler = $client->request('DELETE', '/tags/1');

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(true,$results->result);
        
    }
    
    
    public function testDeleteTagNotFound()
    {
        $client = $this->createClient();
        $crawler = $client->request('DELETE', '/tags/101');

        # request returned 200 ok
        $this->assertEquals(
            404,
            $client->getResponse()->getStatusCode()
        );
        
        
    }
    
    public function testTagUpdate()
    {
        $client = $this->createClient();
        $crawler = $client->request('PUT', '/tags/1',array('tagTitle' => 'mytitle','tagWeight'=> 4.56));

        
        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(true,$results->result);
            
    }
    
    
    public function testTagUpdateNoChanges()
    {
        $client = $this->createClient();
        $crawler = $client->request('PUT', '/tags/1',array('tagTitle' => 'rwod4','tagWeight'=> 1));


        
        # request returned 200 ok
        $this->assertEquals(
            304,
            $client->getResponse()->getStatusCode()
        );
        
    }
    
    
    public function testTagUpdateMissingTitle()
    {
        $client  = $this->createClient();
        $crawler = $client->request('PUT', '/tags/1', array('tagWeight'=> 1));

        
        # request returned 200 ok
        $this->assertEquals(
            400,
            $client->getResponse()->getStatusCode()
        );
      
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals('[tagTitle] This value should not be blank.',$results->msg);
        
    }
    
    public function testTagUpdateMissingWeight()
    {
        $client  = $this->createClient();
        $crawler = $client->request('PUT', '/tags/1', array('tagTitle' => 'rwod4'));

        
        # request returned 200 ok
        $this->assertEquals(
            400,
            $client->getResponse()->getStatusCode()
        );
      
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals('[tagWeight] This value should not be blank.',$results->msg);
        
    }
    
    public function testTagUpdateNumericButStringTitle()
    {
        $client  = $this->createClient();
        $crawler = $client->request('PUT', '/tags/1', array('tagTitle' => '1','tagWeight'=> 1));

        
        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
              
    }
    
    
    public function testTagPost()
    {
        $client  = $this->createClient();
        $crawler = $client->request('POST', '/tags', array('tagTitle' => 'mytitle','tagWeight'=> 1));

        
        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $result = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals('Stored new tag with title mytitle tag at id 101',$result->msg);
        $this->assertEquals(true,$result->result);
              
    }
    
   
    public function testGetTagsEmptyTitle()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags',array(
                                                'limit'    => 10,
                                                'offset'   => 0,
                                                'dir'      => 'asc',
                                                'order'    => 'title',
                                                'tagTitle' => '' 
                                    ));

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(10,count($results->result));
            
    }
    
    
    public function testGetTagsMiniParams()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags',array());

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(100,count($results->result));
        
    }
    
    
    public function testGetTagsOrderByCreatedDate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags',array('order'=>'created'));

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(100,count($results->result));
        
    }
    
    
    public function testGetTagsOrderByWeightDate()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags', array('order'=>'weight'));

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
        $this->assertEquals(100,count($results->result));
        
    }
    
    
    public function testGetTagsDirDesc()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags', array('dir'=>'desc'));

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
    }
    
    
    public function testGetTagsTitleSearch()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/tags', array('dir'=>'desc','order'=>'title','limit' => 10,'tagTitle' => 'r'));

        # request returned 200 ok
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );
        
        $results = json_decode($client->getResponse()->getContent());
        
    }
    
    
}

/* End of File */