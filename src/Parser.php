<?php
namespace tbollmeier\bitsy;

use tbollmeier\parsian\output\Ast;


class Parser extends BaseParser 
{

    public function __construct() 
    {
        parent::__construct();
        $this->setAstTransformations();
    }

    private function setAstTransformations() 
    {
        $g = $this->getGrammar();

        $g->setCustomTermAst("VAR_NAME", function(Ast $ast) {
            return new Ast("var", $ast->getText());
        });
        $g->setCustomTermAst("INT", function(Ast $ast) {
            return new Ast("int", $ast->getText());
        });
        $g->setCustomTermAst("PLUS", function(Ast $ast) {
            return new Ast("plus");
        });
        $g->setCustomTermAst("MINUS", function(Ast $ast) {
            return new Ast("minus");
        });
        $g->setCustomTermAst("MULT", function(Ast $ast) {
            return new Ast("mult");
        });
        $g->setCustomTermAst("DIV", function(Ast $ast) {
            return new Ast("div");
        });
        $g->setCustomTermAst("MOD", function(Ast $ast) {
            return new Ast("mod");
        });


        $g->setCustomRuleAst("program", function(Ast $ast) {

            $ret = new Ast("program");
            $block = $ast->getChildrenById("block")[0];
            foreach ($block->getChildren() as $stmt) {
                $ret->addChild($stmt);
            }

            return $ret;
        });

        $g->setCustomRuleAst("loop", function (Ast $ast) {
            $ret = new Ast("loop");
            $block = $ast->getChildrenById("block")[0];
            foreach ($block->getChildren() as $stmt) {
                $ret->addChild($stmt);
            }
            return  $ret;
        });

        $g->setCustomRuleAst("if_statement", function (Ast $ast) {

            $ret = new Ast("if");

            $children = $ast->getChildren();

            $if = $children[0];
            $condExpr = $children[1];

            $cond = new Ast("condition");
            $cond->addChild($condExpr);
            $ifType = $if->getAttr("type");
            $cond->setAttr("check-type", [
                "IFP" => "is-positive",
                "IFZ" => "is-zero",
                "IFN" => "is-negative"
            ][$ifType]);
            $ret->addChild($cond);

            $action = $children[2];
            $action->setName("action");
            $ret->addChild($action);

            $altAction = $ast->getChildrenById("alt");
            if (!empty($altAction)) {
                $altAction = $altAction[0];
                $altAction->clearId();
                $altAction->setName("alt");
                $ret->addChild($altAction);
            }

            return $ret;
        });


        $g->setCustomRuleAst("expression", function (Ast $ast) {
            $children = $ast->getChildren();
            return  count($children) == 1 ? 
                $children[0] : $ast;
        }); 

        $g->setCustomRuleAst("term", function (Ast $ast) {
            $children = $ast->getChildren();
            return  count($children) == 1 ? 
                $children[0] : $ast;
        }); 

        $g->setCustomRuleAst("signed_factor", function (Ast $ast) {
            $sign = $ast->getChildrenById("sign");
            $factor = $ast->getChildrenById("factor")[0];
            $factor->clearId();
            if (!empty($sign)) {
                $sign = $sign[0];
                $factor->setAttr("sign", $sign->getName());
            }
            return $factor;
        }); 

        $g->setCustomRuleAst("factor", function (Ast $ast) {
            $children = $ast->getChildren();
            return  count($children) == 1 ? 
                $children[0] : $children[1];
        }); 


    }

}