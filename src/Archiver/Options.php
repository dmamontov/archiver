<?php

namespace Archiver;

/**
 * Class Options.
 *
 * @method Options setForce(bool $flag)
 * @method bool    getForce()
 * @method bool    isForce()
 * @method Options setPassword(string $password)
 * @method string  getPassword()
 * @method bool    isPassword()
 * @method Options setComment(string $comment)
 * @method string  getComment()
 * @method bool    isComment()
 * @method Options setCompression(int $compression)
 * @method int     getCompression()
 * @method bool    isCompression()
 */
class Options implements \ArrayAccess
{
    /**
     * @var array
     */
    private array $options;

    public function __construct(array $options = [])
    {
        $this->options = $options;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return null|bool|Options
     */
    public function __call($name, $arguments)
    {
        if (preg_match('/^(set|get|is)([a-zA-Z0-9]+)$/i', $name, $parts)) {
            $parts = array_map('strtolower', $parts);

            switch ($parts[1]) {
                case 'set':
                    $this->offsetSet($parts[2], reset($arguments));

                    return $this;
                case 'get':
                    return $this->offsetGet($parts[2]);
                case 'is':
                    return $this->offsetExists($parts[2]);
            }
        }

        return false;
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->options);
    }

    /**
     * @param mixed $offset
     *
     * @return null|mixed
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->options[$offset] : null;
    }

    public function offsetSet($offset, $value): void
    {
        $this->options[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->options[$offset]);
        }
    }

    public function keys(): array
    {
        return array_keys($this->options);
    }
}
