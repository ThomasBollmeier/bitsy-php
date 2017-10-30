<?php

namespace tbollmeier\bitsy;


class StringOut implements IOutput
{
    private $content = "";

    public function getContent()
    {
        return $this->content;
    }

    public function open() 
    {
        $this->content = "";
    }
    
    public function close() {}
    
    public function write($text)
    {
        $this->content .= $text;
    }
    
    public function writeln($text="") 
    {
        $this->write($text . PHP_EOL);
    }

}