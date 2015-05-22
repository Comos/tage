<?php
/**
 * User: 13leaf
 */
namespace Comos\Tage\Compiler\Node;

/**
 * @package Comos\Tage\Compiler\Node
 */
class IncludeNode extends AbstractNode
{
    public function compile()
    {
        return sprintf("\n".'$this->__c->t(%s,%s);',$this->childNodes['path']->compile(),
            isset($this->childNodes['with'])?$this->childNodes['with']->compile():'array()');
    }
}
