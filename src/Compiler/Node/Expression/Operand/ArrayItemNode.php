<?php
/**
 * User: wangfeng
 * Date: 15-5-21
 * Time: 下午2:14
 */
namespace Comos\Tage\Compiler\Node\Expression\Operand;

use Comos\Tage\Compiler\Node\AbstractNode;

class ArrayItemNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    private $keyNode;
    /**
     * @var AbstractNode
     */
    private $valueNode;

    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
        if(isset($childNodes['key'])){
            $this->keyNode=$childNodes['key'];
        }
        $this->valueNode = $childNodes['value'];
    }

    public function compile()
    {
        if($this->keyNode !== null){
            return sprintf('%s=>%s',$this->keyNode->compile(),$this->valueNode->compile());
        }else{
            return $this->valueNode->compile();
        }
    }

}