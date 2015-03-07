<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:18
 */
namespace Etag\Node;

/**
 * Class AbstractNode
 * Node为Parser解析后得到的语法节点(树)，它将转换为等效的php代码。
 * @package Etag\Node
 */
abstract class AbstractNode
{
    /**
     * @var AbstractNode[] 子节点
     */
    public $childNodes;

    /**
     * 将语法节点等效映射到php代码。该转换过程是递归的
     * @return string
     */
    public function compile(){

    }
}