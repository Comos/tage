<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:54
 */
namespace Comos\Tage\Compiler;

class CompileException extends \Comos\Tage\Exception
{
    public function __construct($filename,$message,$line,$col){
        parent::__construct(sprintf('TageError(%s,%s) at %s:%s',$line,$col,$filename,$message));
    }
}