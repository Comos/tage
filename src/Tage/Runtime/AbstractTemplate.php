<?php
/**
 * User: 13leaf
 */
namespace Tage\Runtime;

abstract class AbstractTemplate
{

    /**
     *
     * @param $vars array 上下文变量
     * 渲染模板
     *            
     */
    public abstract function run($vars);
}