<?php
/* This file has been generated by the Parsian parser generator 
 * (see http://github.com/thomasbollmeier/parsian)
 * 
 * DO NOT EDIT THIS FILE!
 */
namespace tbollmeier\bitsy;

use tbollmeier\parsian as parsian;
use tbollmeier\parsian\output\Ast;


class BaseParser extends parsian\Parser
{
    public function __construct()
    {
        parent::__construct();

        $this->configLexer();
        $this->configGrammar();
    }

    private function configLexer()
    {

        $lexer = $this->getLexer();

        $lexer->addCommentType("{", "}");


        $lexer->addSymbol("=", "EQ");
        $lexer->addSymbol("+", "PLUS");
        $lexer->addSymbol("-", "MINUS");
        $lexer->addSymbol("*", "MULT");
        $lexer->addSymbol("/", "DIV");
        $lexer->addSymbol("%", "MOD");
        $lexer->addSymbol("(", "LPAR");
        $lexer->addSymbol(")", "RPAR");

        $lexer->addTerminal("/[_A-Za-z]+/", "VAR_NAME");
        $lexer->addTerminal("/[0-9]+/", "INT");

        $lexer->addKeyword("BEGIN");
        $lexer->addKeyword("END");
        $lexer->addKeyword("ELSE");
        $lexer->addKeyword("END");
        $lexer->addKeyword("IFZ");
        $lexer->addKeyword("IFP");
        $lexer->addKeyword("IFN");
        $lexer->addKeyword("LOOP");
        $lexer->addKeyword("END");
        $lexer->addKeyword("BREAK");
        $lexer->addKeyword("PRINT");
        $lexer->addKeyword("READ");

    }

    private function configGrammar()
    {

        $grammar = $this->getGrammar();

        $grammar->rule("program",
            $this->seq_1(),
            true);
        $grammar->rule("block",
            $grammar->many($grammar->ruleRef("stmt")),
            false);
        $grammar->rule("stmt",
            $this->alt_1(),
            false);

        $grammar->setCustomRuleAst("stmt", function (Ast $ast) {
            $child = $ast->getChildren()[0];
            $child->clearId();
            return $child;
        });

        $grammar->rule("if_statement",
            $this->seq_2(),
            false);
        $grammar->rule("if_keyword",
            $this->alt_2(),
            false);

        $grammar->setCustomRuleAst("if_keyword", function (Ast $ast) {
            $child = $ast->getChildren()[0];
            $child->clearId();
            return $child;
        });

        $grammar->rule("loop",
            $this->seq_4(),
            false);
        $grammar->rule("break",
            $grammar->term("BREAK"),
            false);

        $grammar->setCustomRuleAst("break", function (Ast $ast) {
            $res = new Ast("break", "");
            return $res;
        });

        $grammar->rule("print",
            $this->seq_5(),
            false);

        $grammar->setCustomRuleAst("print", function (Ast $ast) {
            $res = new Ast("print", "");
            $local_1 = $ast->getChildrenById("expr")[0];
            $local_1->clearId();
            $res->addChild($local_1);
            return $res;
        });

        $grammar->rule("read",
            $this->seq_6(),
            false);

        $grammar->setCustomRuleAst("read", function (Ast $ast) {
            $res = new Ast("print", "");
            $local_1 = $ast->getChildrenById("var")[0];
            $local_1->clearId();
            $res->addChild($local_1);
            return $res;
        });

        $grammar->rule("assignment",
            $this->seq_7(),
            false);

        $grammar->setCustomRuleAst("assignment", function (Ast $ast) {
            $res = new Ast("assign", "");
            $local_1 = $ast->getChildrenById("lhs")[0];
            $local_1->clearId();
            $res->addChild($local_1);
            $local_2 = $ast->getChildrenById("rhs")[0];
            $local_2->clearId();
            $res->addChild($local_2);
            return $res;
        });

        $grammar->rule("expression",
            $this->seq_8(),
            false);
        $grammar->rule("add_op",
            $this->alt_3(),
            false);

        $grammar->setCustomRuleAst("add_op", function (Ast $ast) {
            $child = $ast->getChildren()[0];
            $child->clearId();
            return $child;
        });

        $grammar->rule("term",
            $this->seq_10(),
            false);
        $grammar->rule("signed_factor",
            $this->seq_12(),
            false);
        $grammar->rule("mul_op",
            $this->alt_4(),
            false);

        $grammar->setCustomRuleAst("mul_op", function (Ast $ast) {
            $child = $ast->getChildren()[0];
            $child->clearId();
            return $child;
        });

        $grammar->rule("factor",
            $this->alt_5(),
            false);

        $grammar->setCustomRuleAst("factor", function (Ast $ast) {
            $child = $ast->getChildren()[0];
            $child->clearId();
            return $child;
        });

        $grammar->rule("group",
            $this->seq_13(),
            false);

        $grammar->setCustomRuleAst("group", function (Ast $ast) {
            $res = new Ast("group", "");
            $local_1 = $ast->getChildrenById("expr")[0];
            $local_1->clearId();
            $res->addChild($local_1);
            return $res;
        });


    }

    private function alt_1()
    {
        $grammar = $this->getGrammar();

        return $grammar->alt()
            ->add($grammar->ruleRef("if_statement"))
            ->add($grammar->ruleRef("loop"))
            ->add($grammar->ruleRef("break"))
            ->add($grammar->ruleRef("print"))
            ->add($grammar->ruleRef("read"))
            ->add($grammar->ruleRef("assignment"));
    }

    private function alt_2()
    {
        $grammar = $this->getGrammar();

        return $grammar->alt()
            ->add($grammar->term("IFZ"))
            ->add($grammar->term("IFP"))
            ->add($grammar->term("IFN"));
    }

    private function alt_3()
    {
        $grammar = $this->getGrammar();

        return $grammar->alt()
            ->add($grammar->term("PLUS"))
            ->add($grammar->term("MINUS"));
    }

    private function alt_4()
    {
        $grammar = $this->getGrammar();

        return $grammar->alt()
            ->add($grammar->term("MULT"))
            ->add($grammar->term("DIV"))
            ->add($grammar->term("MOD"));
    }

    private function alt_5()
    {
        $grammar = $this->getGrammar();

        return $grammar->alt()
            ->add($grammar->term("INT"))
            ->add($grammar->term("VAR_NAME"))
            ->add($grammar->ruleRef("group"));
    }


    private function seq_1()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("BEGIN"))
            ->add($grammar->ruleRef("block", "block"))
            ->add($grammar->term("END"));
    }

    private function seq_10()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->ruleRef("signed_factor"))
            ->add($grammar->many($this->seq_11()));
    }

    private function seq_11()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->ruleRef("mul_op"))
            ->add($grammar->ruleRef("factor"));
    }

    private function seq_12()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->opt($grammar->ruleRef("add_op", "sign")))
            ->add($grammar->ruleRef("factor", "factor"));
    }

    private function seq_13()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("LPAR"))
            ->add($grammar->ruleRef("expression", "expr"))
            ->add($grammar->term("RPAR"));
    }

    private function seq_2()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->ruleRef("if_keyword"))
            ->add($grammar->ruleRef("expression"))
            ->add($grammar->ruleRef("block"))
            ->add($grammar->opt($this->seq_3()))
            ->add($grammar->term("END"));
    }

    private function seq_3()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("ELSE"))
            ->add($grammar->ruleRef("block", "alt"));
    }

    private function seq_4()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("LOOP"))
            ->add($grammar->ruleRef("block", "block"))
            ->add($grammar->term("END"));
    }

    private function seq_5()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("PRINT"))
            ->add($grammar->ruleRef("expression", "expr"));
    }

    private function seq_6()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("READ"))
            ->add($grammar->term("VAR_NAME", "var"));
    }

    private function seq_7()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->term("VAR_NAME", "lhs"))
            ->add($grammar->term("EQ"))
            ->add($grammar->ruleRef("expression", "rhs"));
    }

    private function seq_8()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->ruleRef("term"))
            ->add($grammar->many($this->seq_9()));
    }

    private function seq_9()
    {
        $grammar = $this->getGrammar();

        return $grammar->seq()
            ->add($grammar->ruleRef("add_op"))
            ->add($grammar->ruleRef("term"));
    }


}
