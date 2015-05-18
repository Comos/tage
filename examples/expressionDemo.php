<?php
/**
 * startup like this:
 * cd into example dir:
 * php -S 0.0.0.0:8080
 * open browser and type in http://localhost:8080/expressionDemo.php
 *
 * User: 13leaf
 */
require_once join(DIRECTORY_SEPARATOR,[__DIR__,'..','src','Autoloader.php']);
Comos\Tage\Autoloader::register();
$code = isset($_REQUEST['code'])?$_REQUEST['code']:'1+2*3/4';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ExpressionDemo</title>
        <style>
            form{
                padding:40px;
                width:1000px;
                margin:0 auto;
            }
            form>textarea{
                width:100%;
            }
            #btnSubmit{
                width:100px;
                height: 40px;
                margin:20px 0;
                font-weight: bold;
            }
            pre.code{
                font-size: 20px;
                color:#00008b;
                -webkit-box-shadow: 1px 2px 7px 1px #000000   ;
                -moz-box-shadow: 1px 2px 7px 1px #000000   ;
                box-shadow: 1px 2px 7px 1px #000000   ;
                padding: 10px;
                line-height: 24px;
                position: relative;
                padding-left: 45px;
            }
            code.bracket.active{
                color:#ffffff;
                background-color: #000000;
            }
        </style>
    </head>
    <body>
        <form method="post">
            <h1><a href="/">Home</a>/Expression Demo</h1>
            <pre class="code">
                <?php
                if(!empty($code)){
                    try{
                        $tplCode = '{{'.$code.'}}';
                        $lexer = new \Comos\Tage\Compiler\Lexer();
                        $tokenStream=$lexer->lex($tplCode,'TageLexerDemoCode');
                        $tokenStream->next();
                        $expressionParser = new \Comos\Tage\Compiler\Parser\ExpressionParser();
                        $expressionNode=$expressionParser->parse($tokenStream);
                        $tokenStream->expect(\Comos\Tage\Compiler\Token::TYPE_TAG_END);
                        $res = $expressionNode->compile();
                        $transform = '';
                        $uuid=0;
                        foreach(preg_split('/(?<!^)(?!$)/u',$res) as $char){
                            if($char == '('){
                                $uuid++;
                                $transform .= sprintf('<code class="bracket bracket-%s"> (',$uuid);
                            }elseif($char == ')'){
                                $transform .= ') </code>';
                            }else{
                                $transform.=$char;
                            }
                        }
                        echo trim($transform);
                    }catch(\Exception $ex){
                        $cls= get_class($ex);
                        $msg=$ex->getMessage();
                        echo sprintf('<strong style="color:red;">%s:%s</strong>',$cls,$msg);
                    }
                }else{
                    echo "<strong>Please type code and submit:</strong>";
                }
                ?>
            </pre>
            <textarea name="code" cols="100" rows="20"><?php echo htmlspecialchars($code,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');?></textarea>
            <button type="submit" id="btnSubmit">Submit</button>
        </form>
    <script type="text/javascript">
        (function(){
            var brackets=document.querySelectorAll('.bracket');
            for(var i=0;i<brackets.length;i++){
                brackets[i].addEventListener('mouseover',function(e){
                    e.target.classList.add('active');
                },false);
                brackets[i].addEventListener('mouseout',function(e){
                    e.target.classList.remove('active');
                },false);
            }
        })();
    </script>
    </body>
</html>