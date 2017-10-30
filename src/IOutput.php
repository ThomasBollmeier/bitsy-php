<?php
namespace tbollmeier\bitsy;


interface IOutput 
{
    public function open();
    public function close();
    public function write($text);
    public function writeln($text="");
}