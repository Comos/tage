<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:18
 */
namespace Comos\Tage\Compiler\Node;
use Comos\Tage\Compiler\Token;

/**
 * Class AbstractNode
 * Node为Parser解析后得到的语法节点(树)，它将转换为等效的php代码。
 * @package Tage\Compiler\Compiler\Node
 */
abstract class AbstractNode
{
    /**
     * @var Token[] tokens
     */
    public $tokens;

    /**
     * @var AbstractNode[] 子节点
     */
    public $childNodes;

    public function __construct(array $tokens,array $childNodes=[])
    {
        $this->tokens=$tokens;
        $this->childNodes=$childNodes;
    }


    /**
     * 将语法节点等效映射到php代码。该转换过程是递归的
     * @return string
     */
    public function compile(){

    }

}