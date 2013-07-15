<?php
namespace QuickTag\Silex\Controllers;

use DateTime;
use Silex\Application,
    Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Validator\Constraints as Assert;
use QuickTag\QuickTagException;
use QuickTag\Model\StoredTag;

class TagProvider extends BaseProvider implements ControllerProviderInterface
{
    
    
    public function connect(Application $app)
    {
        parent::connect($app);
        
        # define the routes    
        $controllers = $app['controllers_factory'];
        
        $controllers->get('/tags/{tag}', array($this,'getTagAction'))
                    ->assert('tag', '\d+')
                    ->convert('tag',array($this,'lookupTag'));
        
        $controllers->put('/tags/{tag}', array($this,'putTagsAction'))
                    ->assert('tag', '\d+')
                    ->convert('tag',array($this,'lookupTag'));
       
        $controllers->delete('/tags/{tag}', array($this,'deleteTagsAction'))
            ->assert('tag', '\d+')
            ->convert('tag',array($this,'lookupTag'));
       
        $controllers->get('/tags', array($this,'getTagsAction'));
        $controllers->post('/tags', array($this,'postTagAction'));
        
        return $controllers;
    }
    
    
    public function lookupTag($tag)
    {
        $result = $this->getTagLibrary()->lookupTag((integer)$tag);
            
        if(!$result instanceof StoredTag) {
            $this->getContainer()->abort(404,'Tag Entity not found at ID '.$tag);
        } 
        
        return $result;
    }
    
    
    /**
      *  Delete a tag
      *
      *  @access public
      *  @return string a json response
      */
    public function deleteTagsAction(Application $app, Request $req, StoredTag $tag)
    {
        $response = array(
            'msg'    => null,
            'result' => null
        );
        
        # remove the storedTag 
        $response['result'] = $this->getTagLibrary()->removeTag($tag);                
        $response['msg']    = 'Removed Tag with id ' .$tag->getTagId();
        
        return $this->response($response,200);
    }
    
    /**
      *  Update a tag 
      *
      *  @access public
      *  @return string a json response
      */
    public function putTagsAction(Application $app, Request $req, StoredTag $tag)
    {
        $response = array(
            'msg'    => null,
            'result' => null
        );
        
        # Validate Params
        $errors   = $this->getValidator()->validateValue(array(
                                           'tagTitle'  => $req->get('tagTitle'),
                                           'tagWeight' => $req->get('tagWeight')
                                        ), $this->getValidationRules());
            
        if (count($errors) > 0) {
                $this->getContainer()->abort(400,$this->serializeValidationErrors($errors));
        } 
        
        # update the tag
        $tag->setTitle((string)$req->get('tagTitle'));
        $tag->setWeight((float)$req->get('tagWeight'));
                    
        $response['result'] = $this->getTagLibrary()->storeTag($tag);
        $response['msg']    = "Tag modified at index ".$tag->getTagId();
        
        # was the tag updated or no changes made?        
        if(!$response['result']) {
            $this->getContainer()->abort(304,"Tag not modified");
        }
    
        return $this->response($response,200);
    }
    
    /**
      *  Creates a Tag
      *
      *  @access public
      *  @return string a json response
      */
    public function postTagAction(Application $app, Request $req)
    {
        $response = array(
            'msg'    => null,
            'result' => null
        );
     
        # Validate Params
        $errors   = $this->getValidator()->validateValue(array(
                                           'tagTitle'  => $req->get('tagTitle'),
                                           'tagWeight' => $req->get('tagWeight')
                                        ), $this->getValidationRules());
            
        if (count($errors) > 0) {
                $this->getContainer()->abort(400,$this->serializeValidationErrors($errors));
        } 
        
        $tag = new StoredTag();
        
        $tag->setTitle((string)$req->get('tagTitle'));
        $tag->setWeight((float)$req->get('tagWeight'));
        $tag->setTagCreated(new DateTime());
        
        
        $response['result'] = $this->getTagLibrary()->storeTag($tag);        
        $response['msg']    = sprintf('Stored new tag with title %s tag at id %s',$tag->getTitle(),$tag->getTagId());
        
        return $this->response($response,200);
    
    }
    
    /**
      *  A Tag search
      *
      *  @access public
      *  @return string a json response
      */
    public function getTagsAction(Application $app, Request $req)
    {
        $response = array(
            'msg'    => null,
            'result' => array()
        );
        
        $limit    = (int)$req->get('limit',100);
        $offset   = (int)$req->get('offset',0);
        $dir      = $req->get('dir','asc');
        $order    = $req->get('order','title');
        $tagTitle = $req->get('tagTitle','');
        $user     = $req->get('user',null);
        
        $errors = $this->getValidator()->validateValue(array(
                                                        'limit'    => $limit,
                                                        'offset'   => $offset,
                                                        'dir'      => $dir,
                                                        'order'    => $order,
                                                        'tagTitle' => $tagTitle,
                                                        'user'     => $user,
                                                        ) , $this->getQueryValidationRules());
            
        if (count($errors) > 0) {
            $this->getContainer()->abort(400,$this->serializeValidationErrors($errors));
        }
        
        # fetch query object
        $query = $this->getTagLibrary()->findTag()
            ->start()
                ->limit($limit)
                ->offset($offset);
            
        # pick a orderBy clause
        if($order === 'title') {
            $query->orderByTitle($dir);
        } elseif ($order === 'title') {
           $query->orderByWeight($dir);
        } else {
            $query->orderByCreated($dir);
        }
        
        # filter by user context
        if($user !== null) {
            $query->filterByUserContext($user);
        }
        
        # search for a tag starting with xxx        
        if($tagTitle !== '') {
            $query->filterByNameStartsWith($tagTitle);
        }
        
        # run the query and push results through formatter
        $resultCollection = $query->end()->find();
        $formatter = $this->getTagFormatter();
        
        foreach($resultCollection as $tag) {
            $response['result'][] =  $formatter->toArray($tag);    
        }
        
        return $this->response($response,200);
    
    }
    
    
    /**
      *  Load a Single Tag
      *
      *  @access public
      *  @return string a json response
      */
    public function getTagAction(StoredTag $tag)
    {
        $response = array(
            'msg'    => null,
            'result' => array()
        );
            
        $response['result'] = $this->getTagFormatter()->toArray($tag);    
        
        return $this->response($response,200);
    }
    
}
/* End of File */