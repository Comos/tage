<?php
/**
 * User: bigbigant
 * Time: Mar 08 2015
 */
namespace Tage\Runtime;

interface TplPreparer
{

    /**
     *
     * @param string $name            
     * @param
     *            \Tage\Compiler\Compiler
     * @throws \Tage\Runtime\FailToLoadTplException
     * @return string URI to compiled code
     *         do load tpl source
     */
    public function prepare($name, \Tage\Compiler\Compiler $compiler);
}
