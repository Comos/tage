<?php
namespace Tage\Runtime;

class Runtime
{    
    /**
     * 
     * @var Context
     */
    private $context;
    /**
     * 
     * @param TplPreparer $tplPreparer
     */
    public function __construct(TplPreparer $tplPreparer)
    {
        $this->context = new Context($tplPreparer);
    }
    
    /**
     * Output rendering result directly without OB.
     * Errors and exceptions may cause imcomplete output. 
     * 
     * @param string $tplId
     * @param array $data
     * @throws \Exception
     */
    public function displayWithoutOB($tplId, $data = [])
    {
        $this->context->t($tplId, $data);
    }
    /**
     * Returns rendering result without any output.
     * 
     * @param string $tplId
     * @param array $data
     * @throws \Exception
     * @return string
     * @todo deal with OBLevel
     */
    public function fetch($tplId, $data = [])
    {
        ob_start();
        try {
            $this->displayWithoutOB($tplId, $data);
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
    public function display($tplId, $data = [])
    {
        echo $this->fetch($tplId, $data);
    }
}
