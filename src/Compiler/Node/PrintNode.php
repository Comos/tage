<?php
/**
 * User: 13leaf
 */
namespace Comos\Tage\Compiler\Node;
use Comos\Tage\Compiler\Token;

/**
 * @package Comos\Tage\Compiler\Node
 */
class PrintNode extends AbstractNode
{
    public function __construct(AbstractNode $expressionNode=null,Token $textToken=null)
    {
        parent::__construct(['text'=>$textToken],['expression'=>$expressionNode]);
    }


    public function compile()
    {
        if(isset($this->tokens['text'])){
            return sprintf("\necho <<<'TEXT'
%s
TEXT;", $this->tokens['text']->getValue());
        }else{
            return sprintf("\necho %s;",$this->childNodes['expression']->compile());
        }
    }
}
