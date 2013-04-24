<?php

/*
 * This file is part of Nest.
 *
 * Copyright (c) 2013 Anthony Regeda
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Nest\Tests;

use \Nest;

/**
 * Nest Test
 *
 * @package nest
 * @author Anthony Regeda
 */
class NestTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleCall()
    {
        $client = $this->getMock('Client', array('get'));

        $client
            ->expects($this->once())
            ->method('get')
            ->with($this->equalTo('foo'))
            ->will($this->returnValue('baz'));

        $nest = new Nest('foo', $client);

        $this->assertSame('baz', $nest->get());
    }

    public function testToString()
    {
        $nest = new Nest('f00', new Client());

        $this->assertSame('f00:bar', (string) $nest['bar']);
    }

    public function testCallWithArguments()
    {
        $client = $this->getMock('Client', array('set'));

        $client
            ->expects($this->once())
            ->method('set')
            ->with(
                $this->equalTo('f00:bar'),
                $this->equalTo('hello world')
            )
            ->will($this->returnValue(true));

        $nest = new Nest('f00', $client);

        $this->assertTrue($nest['bar']->set('hello world'));
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testExplicitSetNotImplemented()
    {
        $nest = new Nest('f00', new Client());

        $nest['baz'] = new Nest('baz', new Client());
    }

    public function testUnsetExistedNest()
    {
        $nest = new Nest('f00', new Client());

        $this->assertFalse(isset($nest['baz']));
        $this->assertInstanceOf(get_class($nest), $nest['baz']);
        $this->assertTrue(isset($nest['baz']));

        unset($nest['baz']);
        $this->assertFalse(isset($nest['baz']));
    }
}
