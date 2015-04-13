<?php
/**
 * User: bigbigant
 * Time: Mar 08 2015
 */
namespace Comos\Tage\Runtime;

interface TplPreparer
{

    /**
     *
     * @param string $name
     * @throws \Comos\Tage\Runtime\FailToLoadTplException
     * @return \Comos\Tage\Runtime\AbstractTemplate
     */
    public function prepare($name);
}
