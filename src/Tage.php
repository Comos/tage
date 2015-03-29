<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午6:09
 */
namespace Tage;

use Tage\Util\Config;
use Tage\Runtime\FsBasedTplPreparer;
use Tage\Compiler\Compiler;
use Tage\Runtime\Runtime;

class Tage
{

    /**
     *
     * @var \Tage\Runtime\TplPreparer
     */
    private $tplPreparer;

    /**
     *
     * @var \Tage\Runtime\Runtime
     */
    private $runtime;

    /**
     *
     * @param array $options            
     */
    public function __construct($options = array())
    {
        $conf = Config::fromArray($options);
        $tplDir = $conf->rstr('tplDir');
        $compiledTplDir = $conf->rstr('compiledTplDir');
        $compiler = new Compiler();
        $this->tplPreparer = new FsBasedTplPreparer([
            'tplDir' => $tplDir,
            'compiledTplDir' => $compiledTplDir
        ], $compiler);
        
        $this->runtime = new Runtime($this->tplPreparer);
    }

    /**
     *
     * @param string $tplName            
     * @param array $data            
     */
    public function display($tplName, $data)
    {
        $this->getRuntime()->display($tplName, $data);
    }

    /**
     *
     * @param string $tplName            
     * @param array $data            
     * @return string
     */
    public function fetch($tplName, $data)
    {
        return $this->getRuntime()->fetch($tplName, $data);
    }

    /**
     *
     * @return \Tage\Runtime\Runtime
     */
    public function getRuntime()
    {
        return $this->runtime;
    }
}