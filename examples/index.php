<!DOCTYPE html>
<html>
    <head>
        <title>Tage Example Demos</title>
        <meta charset="utf-8">
    </head>
    <body>
        <h1>Tage Example Demos</h1>
        <dl>
            <dt><a href="lexerDemo.php">LexerDemo</a></dt>
            <dd>
                <p>Lexer splits tage template code to <em>Tokens</em>.</p>
                <p>A <em>Token</em> contains information about where is it at source code,and what is it type.</p>
                <p>By the way,a TextToken and NumberToken which both have value 1 are very different.(Eg: <code>1{{1}}</code>)</p>
                <p>Go LexerDemo ,you can type sample code and submit to see how it works.</p>
            </dd>
            <dt><a href="expressionDemo.php">ExpressionDemo</a></dt>
            <dd>
                <p>Tage parse and compile your expression to php code.</p>
                <p>Go ExpressionDemo,you can type sample expression and submit to see how it works.</p>
            </dd>
        </dl>
    </body>
</html>