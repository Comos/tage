<?php
/**
 * User: bigbigant
 * Date: Mar 08 2015
 */
namespace Etag\Runtime;

class FsBasedTplPreparer implements TplPreparer {
    private $_tplDir;
    private $_compiledTplDir;
    public function __construct($config) {
        $conf = \Etag\Util\Config::fromArray($config);
        $this->_tplDir = $conf->str('tplDir', null, true);
	$this->_compiledTplDir = $conf->str('compiledTplDir', null, true);
    }

    public function prepare($name, \Etag\Compiler\Compiler $compiler = null) {
        
    }
}

