<?php
namespace QuickTag\Silex\Controllers;

use Silex\Application,
    Silex\ControllerProviderInterface;
use Symfony\Component\Validator\ConstraintViolationList,
    Symfony\Component\HttpFoundation\JsonResponse;
use QuickTag\QuickTagException;


class BaseProvider implements ControllerProviderInterface
{
    
    protected $index;
    
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
        throw new QuickTagException('Method not implemented');
    }
    
    
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
    
}
/* End of File */