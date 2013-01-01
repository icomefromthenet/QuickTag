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
        $controllers = $app['controllers_factory'];
        $controllers->get('/tags/{id}', array($this,'getTagAction'))->assert('id', '\d+');
        $controllers->put('/tags/{id}', array($this,'putTagsAction'))->assert('id', '\d+');
        $controllers->delete('/tags/{id}', array($this,'deleteTagsAction'))->assert('id', '\d+');
        $controllers->get('/tags', array($this,'getTagsAction'));
        $controllers->post('/tags', array($this,'postTagsAction'));
        
        return $controllers;
    }
    
    /**
      *  Delete a tag
      *
      *  @access public
      *  @return string a json response
      */
    public function deleteTagsAction(Application $app, Request $req, $id)
    {
        $response = array(
            'msg'    => null,
            'result' => null
        );
        $code = 200;
        
        
        try {
            $tag = $app[$this->index];
            
            
            
            
        
        } catch(\Exception $e) {
            $code = 500;
            $response['msg'] = $e->getMessage();
            $response['result'] = array();
            $app['monolog']->notice($e->getMessage());
        }
        
        
        return $this->response($response,$code);
    
    }
    
    /**
      *  Update a tag 
      *
      *  @access public
      *  @return string a json response
      */
    public function putTagsAction(Application $app, Request $req, $id)
    {
        $response = array(
            'msg'    => null,
            'result' => null
        );
        $code = 200;
        
        
        try {
            $tag = $app[$this->index];
        
        } catch(\Exception $e) {
            $code = 500;
            $response['msg'] = $e->getMessage();
            $response['result'] = array();
            $app['monolog']->notice($e->getMessage());
        }
        
        
        return $this->response($response,$code);
    
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
        $code = 200;
        
        
        try {
            $tag = $app[$this->index];
        
        } catch(\Exception $e) {
            $code = 500;
            $response['msg'] = $e->getMessage();
            $response['result'] = array();
            $app['monolog']->notice($e->getMessage());
        }
        
        
        return $this->response($response,$code);
    
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
            'result' => null
        );
        $code = 200;
        
        
        try {
            $tag = $app[$this->index];
            
             if(($now = $req->get('now')) === null) {
                $now = date('Y-m-s H:m:s');
            }
            
            if(($iterations = $req->get('iterations')) === null) {
                $iterations = 10;
            }
            
            # filter query params and assign default values
            $constraint = new Assert\Collection(array(
                                'now'        => new Assert\DateTime(),
                                'iterations' => new Assert\Range(array('min' =>1 ,'max' =>100)),
                        ));
            
            
            $errors = $app['validator']->validateValue(array('now' => $now,'iterations'  => $iterations,), $constraint);
            
            if (count($errors) > 0) {
                throw new LaterJobException($this->serializeValidationErrors($errors));
            }
            
        
        } catch(\Exception $e) {
            $code = 500;
            $response['msg'] = $e->getMessage();
            $response['result'] = array();
            $app['monolog']->notice($e->getMessage());
        }
        
        
        return $this->response($response,$code);
    
    }
    
    
    /**
      *  Load a Single Tag
      *
      *  @access public
      *  @return string a json response
      */
    public function getTagAction(Application $app, Request $req, $id)
    {
        $response = array(
            'msg'    => null,
            'result' => array()
        );
        $code = 200;
        
        
        try {
            $tag       = $app[$this->index];
            $formatter = $app[$this->index.'.tagFormatter'];
            
            # do lookup with the service api
            $result = $tag->lookupTag((integer)$id);
            
            # need to pass result to entity formatter?
            if(!$result instanceof StoredTag) {
                $code = 404;
                $response['msg'] = "Tag not found under ".$id;
            } else {
                $response['result'] = $formatter->toArray($result);    
            }
                    
        } catch(\Exception $e) {
            $code = 500;
            $response['msg'] = $e->getMessage();
            $response['result'] = array();
            $app['monolog']->notice($e->getMessage());
        }
        
        
        return $this->response($response,$code);
        
    }
    
}
/* End of File */