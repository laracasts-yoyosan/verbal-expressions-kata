<?php

class Expression
{
    protected $expression = '';

    public static function make()
    {
        return new static;
    }

    public function find($string)
    {
        return $this->add($this->sanitize($string));
    }

    public function then($string)
    {
        return $this->find($string);
    }

    public function anything()
    {
        return $this->add('.*');
    }

    public function maybe($string)
    {
        return $this->add('(?:' . $this->sanitize($string) . ')?');
    }

    public function __toString()
    {
        return $this->getRegex();
    }

    public function getRegex()
    {
        return '/' . $this->expression . '/';
    }

    public function test($string)
    {
        return !!preg_match($this->getRegex(), $string);
    }

    public function anythingBut($value)
    {
        return $this->add('(?!' . $this->sanitize($value) . ').*?');
    }

    public function startOfLine()
    {
        return $this->add('^');
    }

    public function endOfLine()
    {
        return $this->add('$');
    }

    protected function add($value)
    {
        $this->expression .= $value;

        return $this;
    }

    protected function sanitize($value)
    {
        return preg_quote($value, '/');
    }
}
