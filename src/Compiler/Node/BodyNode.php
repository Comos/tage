<?php
/**
 * User: 13leaf
 */
namespace Comos\Tage\Compiler\Node;

/**
 * @package Comos\Tage\Compiler\Node
 */
class BodyNode extends AbstractNode
{
    public function compile()
    {
        $res='';
        foreach($this->childNodes as $childNode){
            $res.=$childNode->compile();
        }
        return $res;
    }
}
