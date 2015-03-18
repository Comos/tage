<?php
/**
 * User: 13leaf
 */
namespace Tage\Compiler\Node;
use Tage\Compiler\Token;

/**
 * @package Tage\Compiler\Node
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
            return sprintf("echo <<<'TEXT'
%s
TEXT;
", $this->tokens['text']->getValue());
        }else{
            return sprintf("echo %s",$this->tokens['expression']);
        }
    }
}
