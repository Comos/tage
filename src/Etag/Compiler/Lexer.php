<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:35
 */

namespace Etag\Compiler;
use Etag\EtagException;

/**
 * Class Lexer
 * @package Etag\Compiler
 */
class Lexer
{
    protected $cursor;
    protected $line;
    protected $length;
    protected $col;
    protected $code;
    protected $filename;
//    protected $chars;
    protected $tokens;

    private static $NAME_HEAD_CHARS=[];
    private static $NAME_CHARS=[];
    private static $PUNCTUATIONS=[];
    private static $OPERATORS=[];
    private static $NUMBER_CHARS=[];

    public function __construct()
    {
        self::$NAME_HEAD_CHARS=array_merge(
            range('a','z'),range('A','Z')
        );
        self::$NAME_CHARS = array_merge(
            self::$NAME_HEAD_CHARS,range('0','9'),['_']
        );
        //$var.name func() func(p1,p2)
        self::$PUNCTUATIONS = str_split('(){}.,|');
        self::$OPERATORS=array_merge(str_split('+-*/%='),['==','>','<','>=','<=','&&','||']);
        self::$NUMBER_CHARS = str_split('0123456789');
    }


    /**
     * 分词并生成Token流
     * @param tplCode string 模板代码
     * @param options array 配置信息
     * @return TokenStream
     */
    public function lex($tplCode,$filename="default",$options=array()){
        //shutdown mbstring overload
        if (function_exists('mb_internal_encoding') && ((int) ini_get('mbstring.func_overload')) & 2) {
            $mbEncoding = mb_internal_encoding();
            mb_internal_encoding('ASCII');
        }
        if(function_exists('mb_detect_encoding')){
            if(mb_detect_encoding($tplCode,'utf-8,ascii,iso-8859-1') === false){
                throw new EtagException('must use ascii compatible encoding');
            }
        }

        //canonicalCode
        $this->code = str_replace(array("\r\n","\r"), "\n",$tplCode);
        //UTF8 can safe compatible ascii
//        $this->chars=preg_split('/(?<!^)(?!$)/u', $tplCode );//split multibyte string
        $this->length = strlen($this->code);

        $this->cursor=0;
        $this->line=1;$this->col=1;

        $this->tokens=[];

        while($this->cursor < $this->length){
            $this->nextToken();
        }

        $this->tokens[] = new Token(Token::TYPE_EOF, '', -1, -1);

        if (isset($mbEncoding)) {
            mb_internal_encoding($mbEncoding);
        }

        return new TokenStream($this->tokens,$filename);
    }

    /**
     * 执行下一个Token解析，可能一次nextToken产生多个Token
     */
    public function nextToken()
    {
        $start=$this->cursor;
        $col=$this->col;
        $line=$this->line;
        while(true){
            if($this->cursor > $start  && $this->cursor>=$this->length){
                $this->tokens[]=new Token(Token::TYPE_TEXT,$this->sub_str($start,$this->cursor-1),$line,$col);
                break;
            }
            $lexPHPCode = $this->test('<?php');
            $lexTag = $this->test('{{');
            if($this->cursor>$start && ($lexPHPCode || $lexTag)){
                $this->tokens[]=new Token(Token::TYPE_TEXT,$this->sub_str($start,$this->cursor-1),$line,$col);
            }
            if($lexPHPCode){
                $this->tokens[]=$this->lexPHPCode();
                break;
            }else if($lexTag){
                $this->lexTag();
                break;
            }
            $this->forward();
        }
    }

    public function lexTag()
    {
        $this->tokens[] = new Token(Token::TYPE_TAG_START, '{{', $this->line, $this->col);
        $this->skip(strlen('{{'));
        while(true){
            if($this->cursor>=$this->length){
                throw new CompileException($this->filename,'Tag not closed',$this->line,$this->col);
            }
            //skip space
            while($this->test([' ',"\n"])){
                $this->forward();
            }

            if($this->test('}}'))
            {
                $this->tokens[]=new Token(Token::TYPE_TAG_END,'}}',$this->line,$this->col) ;
                $this->skip(strlen('}}'));
                break;
            }
            //String
            if($this->test('$')){ //Variable
                $this->tokens[]=$this->lexVar();
            }
            else if($this->test(self::$PUNCTUATIONS)){//Punctuations
                $this->tokens[]=$this->lexPunctuation();
            }
            else if($this->test(self::$NAME_HEAD_CHARS)){//Name
                $this->tokens[]=$this->lexName();
            }
            else if($this->test(["'",'"'])){
                $this->tokens[]=$this->lexString();
            }
            else if($this->test(self::$NUMBER_CHARS)){ //Number
                $this->tokens[]=$this->lexNumber();
            }
            else if($this->test(self::$OPERATORS)){
                $this->tokens[]=$this->lexOperator();
            }
            else{
                throw new CompileException($this->filename,'Invalid token',$this->line,$this->col);
            }
        }
    }

    public function lexOperator()
    {
        $start=$this->cursor;
        $line=$this->line;
        $col=$this->col;
        if($this->test(self::$OPERATORS)){
            $this->forward();
        }
        return new Token(Token::TYPE_OPERATOR,$this->sub_str($start,$this->cursor-1),$line,$col);
    }

    public function lexPunctuation()
    {
        $start=$this->cursor;
        $line=$this->line;
        $col=$this->col;
        if($this->test(self::$PUNCTUATIONS)){
            $this->forward();
        }
        return new Token(Token::TYPE_PUNCTUATION,$this->sub_str($start,$this->cursor-1),$line,$col);
    }

    public function lexNumber()
    {
        $start=$this->cursor;
        $line=$this->line;
        $col=$this->col;
        $isEPreNumber=false;
        while($this->test(self::$NUMBER_CHARS)){
            $this->forward();
            $isEPreNumber=true;
        }
        if($this->test('.')){
            $isEPreNumber=false;
            $this->forward();
            while($this->test(self::$NUMBER_CHARS)){
                $this->forward();
                $isEPreNumber=true;
            }
        }
        if($this->test('e') && $isEPreNumber){
            $this->forward();
            while($this->test(self::$NUMBER_CHARS)){
                $this->forward();
            }
        }
        return new Token(Token::TYPE_NUMBER,$this->sub_str($start,$this->cursor-1),$line,$col);
    }

    public function lexName()
    {
        $start=$this->cursor;
        $col=$this->col;
        $line=$this->line;
        if($this->test(self::$NAME_HEAD_CHARS)){
            $this->forward();
        }
        while($this->cursor<$this->length){
            if($this->test(self::$NAME_CHARS)){
                $this->forward();
            }else{
                return new Token(Token::TYPE_NAME,$this->sub_str($start,$this->cursor-1),$line,$col);
            }
        }
    }

    public function lexVar()
    {
        $start=$this->cursor;
        $col=$this->col;
        $line=$this->line;
        $this->forward();
        if($this->lexName()){
            return new Token(Token::TYPE_VARIABLE,$this->sub_str($start,$this->cursor-1),$line,$col);
        }
    }


    public function lexString()
    {
        $quote = $this->code[$this->cursor];
        $this->forward();
        $start=$this->cursor;
        $line=$this->line;
        $col=$this->col;
        while($this->cursor<$this->length){
            if($this->test('\\')){
                $this->skip(2);
            }
            if($this->test($quote)){
                //end string
                $this->forward();
                return new Token(Token::TYPE_STRING,$this->sub_str($start,$this->cursor-2),$line,$col);
            }
            $this->forward();
        }
    }

    public function lexPHPCode()
    {
        $start=$this->cursor;
        $col=$this->col;
        $line=$this->line;
        $this->skip(strlen('<?php'));
        while(true){
            if($this->test('?>') || $this->cursor >= $this->length){
                $this->skip(strlen('?>'));
                return new Token(Token::TYPE_PHP_CODE, $this->sub_str($start, $this->cursor-1),$line,$col);
            }
            $this->forward();
        }
    }


    protected  function sub_str($start,$end)
    {
        return substr($this->code, $start, $end - $start+1);
    }

    protected function test($symbol)
    {
        if(is_array($symbol)){
            foreach($symbol as $s){
                if($this->test($s)){
                    return true;
                }
            }
        }else{
            foreach(range(0,strlen($symbol)-1) as $i){
                if($this->code[$this->cursor+$i] != $symbol[$i]){
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    protected function forward()
    {
        if($this->code[$this->cursor] == "\n"){
            $this->line += 1;
            $this->col = 1;
        }else{
            $this->col += 1;
        }
        $this->cursor += 1;
    }

    protected function skip($n)
    {
        foreach(range(1,$n) as $i){
            $this->forward();
        }
    }

}
