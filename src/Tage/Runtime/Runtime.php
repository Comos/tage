<?php
namespace Tage\Runtime;

class Runtime
{

    private $tplPreparer;
    /**
     * 
     * @param TplPreparer $tplPreparer
     */
    public function __construct(TplPreparer $tplPreparer)
    {
        $this->tplPreparer = $tplPreparer;
    }
    /**
     * Output rendering result directly without OB.
     * Errors and exceptions may cause imcomplete output. 
     * 
     * @param string $tplName
     * @param array $data
     * @throws \Exception
     */
    public function displayWithoutOB($tplName, $data = [])
    {
        $this->tplPreparer->prepare($tplName)->render($data);
    }
    /**
     * Returns rendering result without any output.
     * 
     * @param string $tplName
     * @param array $data
     * @throws \Exception
     * @return string
     */
    public function fetch($tplName, $data = [])
    {
        ob_start();
        try {
            $this->displayWithoutOB($tplName, $data);
        } catch (\Exception $ex) {
            ob_end_clean();
            throw $ex;
        }
        $data = ob_get_contents();
        ob_end_clean();
        return $data;
    }
    /**
     * Output rendering result.
     * 
     * OB is enabled to prevent imcomplete output cased by Exceptions. 
     * 
     * @param  $template
     * @param array $data
     * @throws \Exception
     */
    public function display($tplName, $data = [])
    {
        echo $this->fetch($tplName, $data);
    }
}
