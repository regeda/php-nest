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

/**
 * Nest main class.
 *
 * @package nest
 * @author  Anthony Regeda <regedaster@gmail.com>
 */
class Nest implements \ArrayAccess
{
    protected $ns;
    protected $client;
    protected $nested = array();

    /**
     * Instantiate the container.
     *
     * @param string $ns     The initial namespace of the nest
     * @param object $client The wrapped client
     */
    public function __construct($ns, $client)
    {
        $this->ns = $ns;
        $this->client = $client;
    }

    /**
     * Checks if a part of a namespace is set.
     *
     * @param string $offset The unique namespace for the nest
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->nested[(string) $offset]);
    }

    /**
     * Gets a new nest with an updated namespace
     *
     * @param string $offset The unique namespace for the nest
     *
     * @return Nest
     */
    public function offsetGet($offset)
    {
        $ns = (string) $offset;

        if (empty($this->nested[$ns])) {
            $this->nested[$ns] = new self($this->ns.':'.$ns, $this->client);
        }

        return $this->nested[$ns];
    }

    /**
     * Not yet implemented
     */
    public function offsetSet($offset, $value)
    {
        throw new \BadMethodCallException('Not yet implemented');
    }

    /**
     * Unsets a part of a namespace.
     *
     * @param string $offset The unique namespace for the nest
     */
    public function offsetUnset($offset)
    {
        unset($this->nested[(string) $offset]);
    }

    /**
     * Calls the method from the wrapped client.
     *
     * Puts a namespace as the first argument.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $callback = array($this->client, $name);

        if ($arguments) {
            array_unshift($arguments, $this->ns);
            return call_user_func_array($callback, $arguments);
        }

        return call_user_func($callback, $this->ns);
    }

    /**
     * Returns a namespace.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->ns;
    }
}
