<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:16
 */
namespace Tage\Compiler\Parser;

use Tage\Compiler\TokenStream;
use Tage\Compiler\Node\Expression\ExpressionNode;

/**
 * Class ExpressionParser
 * @package Tage\Parser
 * 解析表达式
 */
class ExpressionParser extends AbstractParser{

    /**
     * @param $tokenStream TokenStream
     * @return ExpressionNode
     */
    public function parse($tokenStream)
    {
    }
}