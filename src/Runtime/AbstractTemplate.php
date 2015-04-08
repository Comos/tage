<?php
/**
 * User: 13leaf
 */
namespace Tage\Runtime;

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
     * @return \Tage\Runtime\AbstractTemplate
     */
    public function setContext($context)
    {
        $this->__c = $context;
        return $this;
    }
}