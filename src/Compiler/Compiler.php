<?php
/**
 * @author bigbigant, 13leaf
 */
namespace Comos\Tage\Compiler;


class Compiler {
	/**
	 * @param string $tplName
	 * @param string $source
	 * @return string target codes 
	 */
    public function compile($tplName, $source) {
        $lexer  = new Lexer();
        $tokenStream = $lexer->lex($source, $tplName);
        $parser = new Parser\Parser();
        $nodeTree = $parser->parse($tokenStream);
        return $nodeTree->compile();
    }
}
