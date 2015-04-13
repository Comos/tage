<?php
/**
 * User: bigbigant
 * Date: Mar 08 2015
 */
namespace Comos\Tage\Runtime;

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
     * @param Comos\Tage\Compiler\Compiler $compiler            
     */
    public function __construct($options, \Comos\Tage\Compiler\Compiler $compiler)
    {
        $conf = \Comos\Tage\Util\Config::fromArray($options);
        $this->tplDir = $conf->rstr('tplDir');
        $this->compiledTplDir = $conf->rstr('compiledTplDir');
        $this->compiler = $compiler;
    }

    /**
     *
     * @see \Tage\Runtime\TplPreparer::prepare()
     */
    public function prepare($name)
    {
        $clazz = self::nameToClass($name);
        if (\class_exists($clazz)) {
            return new $clazz();
        }
        
        $sourceFile = $this->getSourceFile($name);
        $targetFile = $this->getTargetFile($name);
        $tpl = new FsBasedTpl($sourceFile, $targetFile);
        if (!$tpl->checkTarget()) {
            $source = $tpl->loadSource();
            $tpl->writeTarget($this->compiler->compile($name, $source));
        }
        include $targetFile;
        return new $clazz();
    }

    protected function getSourceFile($name)
    {
        return $this->tplDir . '/' . $name;
    }

    protected function getTargetFile($name)
    {
        return $this->compiledTplDir . '/' . $name . '.php';
    }

    private static function nameToClass($name)
    {
        return '_Tage_Compiled_Template_' .\md5($name);
    }
}

