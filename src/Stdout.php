<?php

namespace tbollmeier\bitsy;


class Stdout implements IOutput 
{
    public function open() {}

    public function close() {}

    public function write($text) 
    {
        print($text);
    }
    
    public function writeln($text="") 
    {
        print($text . PHP_EOL);
    }
}