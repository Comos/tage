<?php
/**
 * User: 13leaf
 * Date: 15-3-7
 * Time: 上午6:09
 */
namespace Comos\Tage;

use Comos\Tage\Util\Config;
use Comos\Tage\Runtime\FsBasedTplPreparer;
use Comos\Tage\Compiler\Compiler;
use Comos\Tage\Runtime\Runtime;

class Tage
{

    /**
     *
     * @var \Comos\Tage\Runtime\TplPreparer
     */
    private $tplPreparer;

    /**
     *
     * @var \Comos\Tage\Runtime\Runtime
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
     * @return \Comos\Tage\Runtime\Runtime
     */
    public function getRuntime()
    {
        return $this->runtime;
    }
}
