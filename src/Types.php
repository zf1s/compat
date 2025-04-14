<?php

namespace Zf1s\Compat;

use InvalidArgumentException;

class Types
{
    /**
     * https://php.watch/versions/8.4/implicitly-marking-parameter-type-nullable-deprecated
     * 
     * @param string $argument
     * @param mixed $value
     * @param string $type
     *
     * @return void
     */
    public static function isNullable($argument, $value, $type)
    {
        if ($value === null
            || self::isOfType($value, $type)) {
            return;
        }
        
        $backtrace = debug_backtrace(0, 2);
        $call = $backtrace[1]['function'];
        if (isset($backtrace[1]['class'])) {
            $call = $backtrace[1]['class'] . ':' . $call;
        }
        
        $valueType = is_object($value)
            ? get_class($value)
            : gettype($value);
        
        throw new InvalidArgumentException(sprintf(
            'Invalid argument "$%s" in "%s".'
                . ' Expected one of: null, %s; got: %s',
            $argument,
            $call,
            $type,
            $valueType
        ));
    }
    
    public static function isOfType($value, $type)
    {
        if ($type === 'string') {
            return is_string($value);
        }
        if ($type === 'int') {
            return is_int($value);
        }
        if ($type === 'float') {
            return is_float($value);
        }
        if ($type === 'bool') {
            return is_bool($value);
        }
        if ($type === 'array') {
            return is_array($value);
        }
        if ($type === 'object') {
            return is_object($value);
        }
        
        return class_exists($type) && $value instanceof $type;
    }
}
