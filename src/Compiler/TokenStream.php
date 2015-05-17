<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:40
 */
namespace Comos\Tage\Compiler;

class TokenStream
{
    /*
     * @var Token[]
     */
    protected $tokens;

    protected $filename;

    protected $current;

    public function __construct($tokens,$filename)
    {
        $this->tokens=$tokens;
        $this->filename=$filename;
        $this->current=-1;
    }

    public function getFileName()
    {
        return $this->filename;
    }


    /**
     * 是否到达结尾
     * @return bool
     */
    public function isEOF()
    {
        return $this->test(Token::TYPE_EOF);
    }

    public function lookNext()
    {
        $next=$this->next();
        $this->current--;
        return $next;
    }

    /**
     * 移动并返回下一个token
     * @return Token
     * @throws ParseException
     */
    public function current()
    {
        if($this->isEOF()){
            throw new ParseException($this->filename,'UnExpected End to template',-1,-1);
        }
        return $this->tokens[$this->current];
    }

    /**
     * @param $tokenTypes
     * @param null $tokenValue
     * @return bool
     */
    public function test($tokenTypes,$tokenValue=null)
    {
        if(!is_array($tokenTypes)){
            $tokenTypes=[$tokenTypes];
        }
        if(!in_array($this->tokens[$this->current+1]->type,$tokenTypes)){
            return false;
        }
        if($tokenValue !== null && $this->tokens[$this->current+1]->value != $tokenValue){
            return false;
        }
        return true;
    }

    /**
     * 移动并返回下一个token
     * @return Token
     * @throws ParseException
     */
    public function next()
    {
        $this->current++;
        if($this->current >= count($this->tokens)){
            throw new ParseException($this->filename,'UnExpected End to template',-1,-1);
        }
        return $this->tokens[$this->current];
    }

    /**
     * 断言下一个Token并返回
     * @param $tokenType
     * @param null $tokenValue
     * @return Token
     * @throws ParseException
     */
    public function expect($tokenType,$tokenValue=null)
    {
        $token = $this->tokens[$this->current + 1];
        if($this->test($tokenType,$tokenValue)){
           return $this->next();
        }else{
            throw new ParseException($this->filename,sprintf('expect %s',$tokenValue==null?Token::typeToString($tokenType):($tokenValue.'['.Token::typeToString($tokenType).']')),$token->line,$token->col);
        }
    }

}