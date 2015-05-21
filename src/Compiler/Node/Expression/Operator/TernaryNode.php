<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午4:30
 */
namespace Comos\Tage\Compiler\Node\Expression\Operator;

use Comos\Tage\Compiler\Node\AbstractNode;

class TernaryNode extends AbstractNode
{
    /**
     * @var AbstractNode
     */
    protected $ifNode;
    /**
     * @var AbstractNode
     */
    protected $ifBodyNode;
    /**
     * @var AbstractNode
     */
    protected $elseBodyNode;

    protected $opToken;

    public function __construct(array $tokens, array $childNodes = [])
    {
        parent::__construct($tokens, $childNodes);
        $this->ifNode = $childNodes['if'];
        $this->ifBodyNode = $childNodes['ifBody'];
        $this->elseBodyNode = $childNodes['elseBody'];
    }

    public function compile()
    {
        return sprintf('(%s?%s:%s)',$this->ifNode->compile(),$this->ifBodyNode->compile(),$this->elseBodyNode->compile());
    }


}
