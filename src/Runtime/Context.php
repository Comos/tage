<?php
namespace Comos\Tage\Runtime;

class Context
{
    /**
     * 
     * @var TplPreparer
     */
    private $tplPreparer;

    public function __construct(TplPreparer $tplPreparer)
    {
        $this->tplPreparer = $tplPreparer;
    }

    /**
     * run sub template
     *
     * @param array $options            
     */
    public function t($id, $options)
    {
        
        $tpl = $this->tplPreparer->prepare($id);
        $tpl->setContext($this)->render($options);
    }
}