<?php
namespace Comos\Tage\Compiler;

use Exception;

class LexerException extends Exception
{
    public function __construct($filename,$message,$line,$col)
    {
        $errorMessage=sprintf('%s error:%s at (%s,%s)',$filename,$message,$line,$col);
        parent::__construct($errorMessage);
    }

}