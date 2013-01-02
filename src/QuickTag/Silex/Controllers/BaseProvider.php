<?php
namespace QuickTag\Silex\Controllers;

use Silex\Application,
    Silex\ControllerProviderInterface;
use Symfony\Component\Validator\ConstraintViolationList,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\Validator\Constraints as Assert;
use QuickTag\QuickTagException;


class BaseProvider implements ControllerProviderInterface
{
    /**
      *  @var string the index to find the tag  
      */
    protected $index;
    
    /**
      *  @var Silex/App 
      */
    protected $app;
    
    /**
      *  Class Constructor
      *
      *  @access public
      *  @param string $index the component api index in silex container
      */
    public function __construct($index)
    {
        $this->index = $index;
    }
    
    
    //------------------------------------------------------------------
    # ControllerProviderInterface
    
    public function connect(Application $app)
    {
        # bind app to his controller
        $this->app = $app;
        
        # bind errro handler
        $app->error(array($this,'handleError'));
        
    }
    
    
    //------------------------------------------------------------------
    # Validation Rules
    
    /**
      *  Return the valdiation rules
      *
      *  @access public
      *  @return Symfony\Component\Validator\Constraints\Collection
      */
    public function getValidationRules()
    {
        return  new Assert\Collection(array(
                                'tagTitle'  => array(
                                                new Assert\Type('string'), new Assert\NotBlank(), new Assert\Length(array('min' =>1, 'max' =>45))
                                            ),
                                'tagWeight' => array(
                                                new Assert\Type('numeric'), new Assert\NotBlank(), new Assert\Range(array('min' =>0 ,'max' =>100))
                                            ),
        ));
    }
    
    /**
      *  Return the valdiation rules for a batch query
      *
      *  @access public
      *  @return Symfony\Component\Validator\Constraints\Collection
      */
    
    public function getQueryValidationRules()
    {
        return  new Assert\Collection(array(
                                'limit'  => array(
                                                new Assert\NotBlank(), new Assert\Length(array('min' =>1, 'max' =>100))
                                            ),
                                'offset' => array(
                                                new Assert\NotBlank(), new Assert\Range(array('min' =>0 ,'max' =>PHP_INT_MAX))
                                            ),
                                'dir'    => array(
                                                new Assert\NotBlank(), new Assert\Choice(array('asc','desc'))
                                            ),
                                'order' =>  array(
                                                new Assert\NotBlank(), new Assert\Choice(array('title','weight','created'))
                                            ),
                                'tagTitle'  => array(
                                                new Assert\Type('string'), new Assert\Length(array('min' =>1, 'max' =>45))
                                            ),
                                'user'      => array(
                                                new Assert\Type('integer'), 
                                            ),
        ));
        
    }
    
    
    //------------------------------------------------------------------
    # Helpers
    
    
    
    /**
      *  Will Serialize the Error Messages From validator into a string
      *
      *  @access public
      *  @return string the error messages
      *  @param ConstraintViolationList $errors
      */
    public function serializeValidationErrors(ConstraintViolationList $errors)
    {
        $myError = array();
        if (count($errors) > 0) {
                
            foreach ($errors as $error) {
                    $myError[] = $error->getPropertyPath().' '.$error->getMessage();
            } 
        }
            
        return implode($myError,PHP_EOL);    
    }
    
    
    /**
     * Convert some data into a JSON response with specific attributes.
     *
     * @param mixed   $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     *
     * @see JsonResponse
     */
    public function response($data = array(), $status = 200, $headers = array())
    {
        
        if(key_exists('result',$data) === false) {
            throw new QuickTagException('Response data must have a result attribute set');
        }
        
        if(key_exists('msg',$data) === false) {
            throw new QuickTagException('Response data must have a message attribute set');
        }
        
        return new JsonResponse($data, $status, $headers);
    }
    
    /**
      *  Error handler for exceptions if app default not been sent.
      *  This handler will not be called if a app handler returns response.
      *
      *  @access public
      *  @return JsonResponse
      */
    public function handleError(\Exception $e, $code)
    {
        switch ($code) {
        case 404:
            $message = 'The requested page could not be found.';
            break;
        case 400:
            $message = $e->getMessage();
            break;
        default:
            $message = 'We are sorry, but something went terribly wrong.';
        }

        # record error to app log.
        $this->app['monolog']->notice($e->getMessage());
        
        return $this->response(array('msg'=> $message,'result' => array()),$code);
    }
    
    /**
      *  Fetch the dependency container
      *
      *  @access public
      *  @return Silex\Application
      */
    public function getContainer()
    {
        return $this->app;
    }
    
    /**
      *  Return an instance of the tag library
      *
      *  @access public
      *  @return QuickTag\Tag
      */
    public function getTagLibrary()
    {
        return $this->app[$this->index];
    }
    
    /**
      *  Fetch a formatter to serialize a stored tag
      *
      *  @access public
      *  @return QuickTag\Silex\Formatter\TagFormatter
      */
    public function getTagFormatter()
    {
        return $this->app[$this->index.'.tagFormatter'];
    }
    
    /**
      *  Fetch the symfony2 validator
      *
      *  @access public
      *  @return Symfony\Component\Validator\Validator
      */
    public function getValidator()
    {
        return $this->app['validator'];
    }
    
}
/* End of File */