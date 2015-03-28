<?php
/**
 * startup like this:
 * cd into example dir:
 * php -S 0.0.0.0:8080
 * open browser and type in http://localhost:8080/lexerDemo.php
 *
 * User: 13leaf
 */
require_once join(DIRECTORY_SEPARATOR,[__DIR__,'..','src','Tage','Autoloader.php']);
\Tage\Autoloader::register();
$code = isset($_REQUEST['code'])?$_REQUEST['code']:'{{"Hello world"}}';
?>

<html>
<head>
    <meta charset="utf-8">
    <title>Tage Lexer Demo</title>
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
    .token.TYPE_PHP_CODE{
        color:blue;
    }

    .token.TYPE_NAME{
        color:darkred;
        padding:0 5px;
    }
    .token.TYPE_TEXT{
        color:#000000;
    }
    .token.TYPE_OPERATOR{
        color:#00008b;
        padding:0 5px;
    }
    .token.TYPE_VARIABLE{
        color:darkred;
    }
    .token.TYPE_STRING{
        color:#006400;
    }
    .token.TYPE_PUNCTUATION{
        color:darkgray;
    }
    code.token{
        -webkit-transition: .2s;
        -moz-transition: .2s;
        -ms-transition: .2s;
        -o-transition: .2s;
        transition: .2s;
        white-space: pre-line;
        /*word-wrap: break-word;*/
    }
    code.token:hover{
        color:#ffffff;
        background-color: #000000;
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
    pre .line-number{
        position: absolute;
        top:0;
        left:0;
        border-right: 1px solid lightgray;
        width:40px;
        height: 100%;
        background-color: #d6dade;
    }
    .line-number .line{
        position:absolute;
        left:2px;
    }
</style>
</head>
<body>
<form method="post">
    <h1>Tage Lexer Demo</h1>
    <pre class="code">
    <div class="line-number"></div>
<?php
if(!empty($code)){
    try{
    $lexer = new \Tage\Compiler\Lexer();
    $tokenStream=$lexer->lex($code,'TageLexerDemoCode');
    $defaultTypeStringMap=array(
        \Tage\Compiler\Token::TYPE_TAG_START=>'{{',
        \Tage\Compiler\Token::TYPE_TAG_END=>"}}",
        \Tage\Compiler\Token::TYPE_TEXT=>' '
    );
    $lastLine=0;
    $lastToken=null;
    while(!$tokenStream->isEOF()){
        $token=$tokenStream->next();
        $type = \Tage\Compiler\Token::typeToString($token->type);
        $val = trim($token->value);
        if(empty($val)){
            $val = $defaultTypeStringMap[$token->type];
        }
//        if($token->type == \Tage\Compiler\Token::TYPE_STRING){
//            $val="'$val'";
//        }
        $lineNumber = '';
        if($lastLine != $token->line){
            $val = "\n" .$val;
            $lastLine=$token->line;
            $lineNumber = str_pad($token->line, 3, ' ',STR_PAD_LEFT);
        }
        $lastToken=$token;
        echo sprintf('<code class="token %s" data-line="%s" title="%s">%s</code>', $type,$lineNumber, $type, htmlspecialchars($val,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8'));
    }
    }catch (Exception $ex){
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
    var codes=document.getElementsByTagName('code');
    var lineNumber = document.getElementsByClassName('line-number')[0];
    for(var i=0;i<codes.length;i++){
        var code = codes[i];
        var line=code.dataset.line;
        if(!line) continue;
        lineNumber.innerHTML +='<span class="line" style="top:'+(code.offsetTop+24)+'px">'+line+'</span>';
    }
</script>

</body>

</html>
