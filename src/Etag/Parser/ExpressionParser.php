<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:16
 */
namespace Etag\Parser;

use Etag\Compiler\TokenStream;
use Etag\Node\Expression\ExpressionNode;

/**
 * Class ExpressionParser
 * @package Etag\Parser
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