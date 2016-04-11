<?php

class ExpressionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_finds_a_string()
    {
        $regex = Expression::make()->find('www');
        $this->assertTrue($regex->test('www'));

        $regex = Expression::make()->then('www');
        $this->assertTrue($regex->test('www'));
    }

    /**
     * @test
     */
    public function it_checks_for_anything()
    {
        $regex = Expression::make()->anything();

        $this->assertTrue($regex->test('blablabla'));
    }

    /**
     * @test
     */
    public function it_maybe_has_a_value()
    {
        $regex = Expression::make()->maybe('http');

        $this->assertTrue($regex->test('http://'));
        $this->assertTrue($regex->test(''));
    }

    /**
     * @test
     */
    public function it_can_chain_method_calls()
    {
        $regex = Expression::make()
            ->find('www')
            ->maybe('.')
            ->then('laracasts');

        $this->assertTrue($regex->test('www.laracasts'));
        $this->assertFalse($regex->test('wwwXlaracasts'));
    }

    /**
     * @test
     */
    public function it_can_exclude_values()
    {
        $regex = Expression::make()
            ->find('foo')
            ->anythingBut('bar')
            ->then('biz');

        $this->assertTrue($regex->test('foobazbiz'));
        $this->assertFalse($regex->test('foobarbiz'));
    }

    /**
     * @test
     */
    public function it_checks_for_start_of_line()
    {
        $regex = Expression::make()
            ->startOfLine()
            ->then('foo');

        $this->assertTrue($regex->test('foo'));
        $this->assertFalse($regex->test('bar'));
    }

    /**
     * @test
     */
    public function it_checks_for_end_of_line()
    {
        $regex = Expression::make()
            ->startOfLine()
            ->then('blabla')
            ->endOfLine();

        $this->assertTrue($regex->test('blabla'));
        $this->assertFalse($regex->test('blablaca'));
    }

    /**
     * @test
     */
    public function it_checks_for_a_valid_github_url()
    {
        $regex = Expression::make()
            ->startOfLine()
            ->then("http")
            ->maybe("s")
            ->then("://")
            ->maybe("www.")
            ->then('github')
            ->anythingBut(" ")
            ->endOfLine();

        $this->assertTrue($regex->test('https://github.com/'));
        $this->assertTrue($regex->test('http://github.com/'));
        $this->assertTrue($regex->test('http://www.github.com/'));
        $this->assertFalse($regex->test('http://www.gaithub.com/'));
    }
}
