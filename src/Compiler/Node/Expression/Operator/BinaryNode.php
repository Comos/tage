<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:30
 */
namespace Comos\Tage\Compiler\Node\Expression\Operator;

use Comos\Tage\Compiler\Node\AbstractNode;

class BinaryNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    protected $leftNode;
    /**
     * @var AbstractNode
     */
    protected $rightNode;

    protected $opToken;

    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
        $this->leftNode = $childNodes['left'];
        $this->rightNode = $childNodes['right'];
        $this->opToken = $tokens['op'];
    }

    public function compile()
    {
        return sprintf('(%s%s%s)',$this->leftNode->compile(),$this->opToken->getValue(),$this->rightNode->compile());
    }


}
