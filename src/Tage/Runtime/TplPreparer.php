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
     * @throws \Tage\Runtime\FailToLoadTplException
     * @return \Tage\Runtime\AbstractTemplate
     */
    public function prepare($name);
}
