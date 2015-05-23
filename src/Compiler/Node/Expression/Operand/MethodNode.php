<?php
/**
 * User: wangfeng
 * Date: 15-5-21
 * Time: 下午2:14
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;

/**
 * Class MethodNode
 * @package Comos\Tage\Compiler\Node\Expression\Operand
 */
class MethodNode extends AbstractNode
{
    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
    }

    public function compile()
    {
        $funcNode=$this->childNodes['right'];
        return sprintf('$this->callMethod(%s,"%s",array(%s))',
            $this->childNodes['left']->compile(),
            $funcNode->tokens['funcName']->value,join(',',array_map(function($x){return $x->compile();},$funcNode->childNodes))
        );
    }

}