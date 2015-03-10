<?php
/**
 * User: bigbigant
 * Time: Mar 08 2015
 */
namespace Etag\Runtime;

interface TplPreparer {
    /**
     * @param string $name
     * @param \Etag\Compiler\Compiler
     * @throws \Etag\Runtime\FailToLoadTplException
     * @return string file path to compiled code
     * do load tpl source
     */
    public function prepare($name, $compiler);
}
