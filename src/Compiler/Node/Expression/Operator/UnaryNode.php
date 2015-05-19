<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:31
 */
namespace Comos\Tage\Compiler\Node\Expression\Operator;

use Comos\Tage\Compiler\Node\AbstractNode;

class UnaryNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    protected $childNode;

    protected $opToken;

    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
        $this->childNode=$childNodes[0];
        $this->opToken = $tokens['op'];
    }

    public function compile()
    {
        return sprintf('(%s%s)',$this->opToken->getValue(),$this->childNode->compile());
    }

}