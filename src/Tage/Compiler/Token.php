<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:40
 */
namespace Tage\Compiler;

use Tage\TageException;

class Token
{
    const TYPE_NUMBER=1;//ex 1 1.2 1e10
    const TYPE_STRING=2;//ex 'str' "str"
    const TYPE_VARIABLE=3;//ex $var
    const TYPE_NAME=4; // ex true false func
    const TYPE_PUNCTUATION=5;//ex () {} | , 这类Token不用于计算，仅用于语法占位
    const TYPE_OPERATOR=6;//ex +-*/
    const TYPE_TAG_START=7;//ex {{
    const TYPE_TAG_END=8;//ex }}
    const TYPE_TEXT=9;//ex <html></html>
    const TYPE_PHP_CODE=10;//ex <?php xxx
    const TYPE_EOF=-1;//the end token

    /**
     * @param $type int
     * @param $value string
     * @param $line int
     * @param $col int
     */
    public function __construct($type,$value,$line,$col)
    {
        $this->type=$type;
        $this->value=$value;
        $this->line=$line;
        $this->col=$col;
    }

    /**
     * @var int
     * 记录模板源码行
     */
    public $line;

    /**
     * @var int
     * 记录模板源码列
     */
    public $col;

    /**
     * @var int
     * 类型
     */
    public $type;
    /**
     * @var string
     * 字面值
     */
    public $value;
    
    /**
     * @return string
     */
    public function getTypename() {
        return self::typeToString($this->type);
    }
    
    /**
     * 
     * @param integer $type
     * @throws TageException
     * @return mixed
     */
    public static function typeToString($type)
    {
        $rc = new \ReflectionClass(self::class);
        $constants = $rc->getConstants();
        $key = array_search($type, $constants, true);
        if (false ===  $key || strpos($key, 'TYPE_') !== 0) {
            throw new TageException(sprintf('Token of type %s does not exist.', $type));
        }
        return $key;
    }
}
