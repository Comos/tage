<?php
/**
 * User: 13leaf
 */
namespace Comos\Tage\Runtime;

abstract class AbstractTemplate
{
    /**
     * @var Context
     */
    protected $__c;
    /**
     *
     * @param $vars array 上下文变量
     * 渲染模板
     *            
     */
    public abstract function render($vars);
    
    /**
     * 
     * @param Context $context
     * @return \Comos\Tage\Runtime\AbstractTemplate
     */
    public function setContext($context)
    {
        $this->__c = $context;
        return $this;
    }

    protected function callMethod($obj,$funcName,$funcArgs)
    {
        if(empty($obj)){
            return $obj;
        }
        if(is_object($obj) && method_exists($obj,$funcName)){
            return $obj->$funcName($funcArgs);
        }
        return NULL;
    }

    protected function getAttribute($obj,$attrName)
    {
        if(empty($obj)){
            return $obj;
        }
        if($attrName !== NULL && isset($obj[$attrName]) ){
            return $obj[$attrName];
        }
        return NULL;
    }
}