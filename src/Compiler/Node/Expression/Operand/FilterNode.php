<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:27
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;

/**
 * Class FilterNode
 * @package Comos\Tage\Compiler\Node\Expression\Operand
 */
class FilterNode extends AbstractNode
{
    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
    }

    public function compile()
    {
        return sprintf('%s(%s)',$this->tokens['filterName']->value,join(',',array_map(function($x){return $x->compile();},$this->childNodes)));
    }

}