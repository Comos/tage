<?php
namespace Tage\Runtime;

class Runtime
{

    private $tplPreparer;

    public function __construct(TplPreparer $tplPreparer)
    {
        $this->tplPreparer = $tplPreparer;
    }

    public function displayWithoutOB($template, $data = [])
    {
        $target = $this->tplPreparer->prepare($template);
        $this->runTemplate($target, $data);
    }

    public function fetch($template, $data = [])
    {
        ob_start();
        try {
            $this->displayWithoutOB($template, $data);
        } catch (\Exception $ex) {
            ob_end_clean();
            throw $ex;
        }
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }

    public function display($template, $data = [])
    {
        echo $this->fetch($template, $data);
    }

    protected function runTemplate($target, $data)
    {
        extract($data);
        include $target;
    }
}
