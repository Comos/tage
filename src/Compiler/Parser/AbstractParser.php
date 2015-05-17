<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午5:21
 */
namespace Comos\Tage\Compiler\Parser;

use Comos\Tage\Compiler\TokenStream;
use Comos\Tage\Compiler\Node\AbstractNode;

abstract class AbstractParser
{

    /**
     *
     * @param $tokenStream TokenStream            
     * @return AbstractNode
     */
    public function parse(TokenStream $tokenStream)
    {

    }
}