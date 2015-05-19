<?php
/**
 * User: 13leaf
 */
namespace Comos\Tage\Compiler\Node;

/**
 * @package Comos\Tage\Compiler\Node
 */
class RootNode extends AbstractNode
{
    public $fileName;

    public function __construct($fileName , array $childNodes = [])
    {
        $this->fileName=$fileName;
        parent::__construct([], $childNodes);
    }

    public function compile(){
        $tpl=<<<'PHP'
<?php
use Comos\Tage\Runtime\AbstractTemplate;

PHP;
        $tpl.=sprintf('class _Tage_Compiled_Template_%s extends AbstractTemplate',md5($this->fileName));
        $tpl.=<<<'PHP'

{
  public function render($vars){
extract($vars);
PHP;
        foreach($this->childNodes as $node){
            $tpl.=$node->compile();
        }
        $tpl.=<<<'PHP'

 }
}
?>
PHP;
        return $tpl;
    }

}
