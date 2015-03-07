<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:40
 */
namespace Etag\Compiler;

class TokenStream
{
    protected $tokens;

    protected $current;

    public function __construct($tokens)
    {
        $this->tokens=$tokens;
    }


    /**
     * 是否到达结尾
     * @return bool
     */
    public function isEOF()
    {

    }

    /**
     * 移动并返回下一个token
     * @return Token
     */
    public function next()
    {

    }

    /**
     * 断言下一个Token并返回
     * @param $tokenType
     * @param null $tokenValue
     * @return Token
     */
    public function expect($tokenType,$tokenValue=null)
    {

    }

}