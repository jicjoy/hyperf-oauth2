<?php

declare(strict_types=1);

namespace Hyperf\Oauth2\Repository\Entity;

class DataObject implements \ArrayAccess
{
    protected $_data = [];

    protected static $_underscoreCache = [];
    
    public function __construct(array $data = [])
    {
         $this->_data = $data;
    }

    public function __get($name)
    {var_dump($name);
        return  $this->_data[$name] ?? null;
    }

    public function __set($name, $value)
    {
       $this->setAttribute($name,$value);
        
        return $this;
    }

    public function __call($method, $args) {
        switch (substr((string)$method, 0, 3)) {
            case 'get':
                $key = $this->_underscore(substr($method, offset: 3));
                return $this->getAttribute($key);
            case 'set':
                $key = $this->_underscore(substr($method, 3));
                $value = isset($args[0]) ? $args[0] : null;
                return $this->setAttribute($key, $value);
            case 'has':
                $key = $this->_underscore(substr($method, 3));
                return isset($this->_data[$key]);
        }

        throw new \BadMethodCallException('Method ' . $method . ' does not exist.');
    }

    
    /**
     * Converts field names for setters and getters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unnecessary preg_replace
     *
     * @param string $name
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }


    public function getAttribute($name)
    {
        return $this->_data[$name] ?? null;
    }

    public function setAttrbitues(array $attributes)
    {
        $this->_data = $attributes;
        return $this;
    }

    public function getAttributes():array {
         return $this->_data;
    }

    public function setAttribute($name, $value)
    {
        $this->_data[$name] = $value;
        return $this;
    }
 
   
    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->$offset);
    }
    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->$offset);
    }
}
