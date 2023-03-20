<?php
namespace Files\View\Helper;

use Laminas\Stdlib\ArrayUtils;
use Laminas\View\Exception;
use ArrayAccess;
use Traversable;

trait Variables {
    
    /**
     * View variables
     *
     * @var array|ArrayAccess|Traversable
     * @psalm-var array|ArrayAccess&Traversable
     */
    protected $variables = [];
    
    /**
     * Property overloading: set variable value
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }
    
    /**
     * Property overloading: get variable value
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (! $this->__isset($name)) {
            return;
        }
        
        $variables = $this->getVariables();
        return $variables[$name];
    }
    
    /**
     * Property overloading: do we have the requested variable value?
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        $variables = $this->getVariables();
        return isset($variables[$name]);
    }
    
    /**
     * Property overloading: unset the requested variable
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        if (! $this->__isset($name)) {
            return;
        }
        
        unset($this->variables[$name]);
    }
    
    /**
     * Called after this view model is cloned.
     *
     * Clones $variables property so changes done to variables in the new
     * instance don't change the current one.
     *
     * @return void
     */
    public function __clone()
    {
        if (is_object($this->variables)) {
            $this->variables = clone $this->variables;
        }
    }
    
    /**
     * Get a single view variable
     *
     * @param  string       $name
     * @param  mixed|null   $default (optional) default value if the variable is not present.
     * @return mixed
     */
    public function getVariable($name, $default = null)
    {
        $name = (string) $name;
        
        if (is_array($this->variables)) {
            if (array_key_exists($name, $this->variables)) {
                return $this->variables[$name];
            }
        } elseif ($this->variables->offsetExists($name)) {
            return $this->variables->offsetGet($name);
        }
        
        return $default;
    }
    
    /**
     * Set view variable
     *
     * @param  string $name
     * @param  mixed $value
     */
    public function setVariable($name, $value)
    {
        $this->variables[(string) $name] = $value;
        return $this;
    }
    
    /**
     * Set view variables en masse
     *
     * Can be an array or a Traversable + ArrayAccess object.
     *
     * @param  array|ArrayAccess|Traversable $variables
     * @param  bool $overwrite Whether or not to overwrite the internal container with $variables
     * @throws Exception\InvalidArgumentException
     */
    public function setVariables($variables, $overwrite = false)
    {
        if (! is_array($variables) && ! $variables instanceof Traversable) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s: expects an array, or Traversable argument; received "%s"',
                __METHOD__,
                is_object($variables) ? get_class($variables) : gettype($variables)
                ));
        }
        
        if ($overwrite) {
            if (is_object($variables) && ! $variables instanceof ArrayAccess) {
                $variables = ArrayUtils::iteratorToArray($variables);
            }
            
            $this->variables = $variables;
            return $this;
        }
        
        foreach ($variables as $key => $value) {
            $this->setVariable($key, $value);
        }
        
        return $this;
    }
    
    /**
     * Get view variables
     *
     * @return array|ArrayAccess|Traversable
     */
    public function getVariables()
    {
        return $this->variables;
    }
    
    /**
     * Clear all variables
     *
     * Resets the internal variable container to an empty container.
     *
     */
    public function clearVariables()
    {
        $this->variables = [];
        return $this;
    }
    
}