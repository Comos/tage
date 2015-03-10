<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:54
 */
namespace Etag\Compiler;

use Etag\EtagException;

class CompileException extends EtagException
{
    public function __construct($message,$line,$col){
        parent::__construct(sprintf('EtagError(%s,%s):%s',$line,$col,$message));
    }
}