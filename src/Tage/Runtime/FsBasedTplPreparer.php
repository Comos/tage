<?php
/**
 * User: bigbigant
 * Date: Mar 08 2015
 */
namespace Tage\Runtime;

class FsBasedTplPreparer implements TplPreparer
{

    /**
     *
     * @var string
     */
    private $tplDir;

    /**
     *
     * @var string
     */
    private $compiledTplDir;

    /**
     *
     * @var \Tage\Compiler\Compiler
     */
    private $compiler;

    /**
     *
     * @param array $options            
     * @param \Tage\Compiler\Compiler $compiler            
     */
    public function __construct($options, \Tage\Compiler\Compiler $compiler)
    {
        $conf = \Tage\Util\Config::fromArray($options);
        $this->tplDir = $conf->rstr('tplDir');
        $this->compiledTplDir = $conf->str('compiledTplDir', null, true);
        $this->compiler = $compiler;
    }

    /**
     *
     * @see \Tage\Runtime\TplPreparer::prepare()
     */
    public function prepare($name)
    {
        $sourceFile = $this->getSourceFile($name);
        $targetFile = $this->getTargetFile($name);
        $tpl = new FsBasedTpl($sourceFile, $targetFile);
        if ($tpl->checkTarget()) {
            return $targetFile;
        }
        $source = $tpl->loadSource();
        $tpl->writeTarget($this->compiler->compile($source));
        return $targetFile;
    }
}

