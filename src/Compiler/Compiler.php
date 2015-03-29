<?php
/**
 * @author bigbigant, 13leaf
 */
namespace Tage\Compiler;

use Tage\Compiler\Node\AbstractNode;

class Compiler {
	/**
	 * @param string $tplName
	 * @param string $sourceCode
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
