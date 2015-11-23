<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 11:27
 */

namespace O2System;

class Bootstrap
{
    protected $_valid_factories = array();

    public function __construct()
    {
        foreach( glob( __DIR__ . '/Factory/*.php' ) as $filepath )
        {
            $this->_valid_factories[ strtolower( pathinfo( $filepath, PATHINFO_FILENAME ) ) ] = $filepath;
        }
    }

    public function __get($factory)
    {
        if(array_key_exists($factory, $this->_valid_factories))
        {
            return $this->_load_factory($factory);
        }
    }

    public function __call($factory, $args = array())
    {
        if(array_key_exists($factory, $this->_valid_factories))
        {
            $factory = $this->_load_factory($factory);
            return call_user_func_array(array($factory, 'create'), $args);
        }
    }

    /**
     * Load driver
     *
     * Separate load_driver call to support explicit driver load by library or user
     *
     * @param   string $factory driver class name (lowercase)
     *
     * @return    object    Driver class
     */
    protected function &_load_factory( $factory )
    {
        if( empty( $this->{$factory} ) )
        {
            if(file_exists($filepath = $this->_valid_factories[$factory]))
            {
                require_once($filepath);

                $class_name = get_called_class() . '\\Factory\\' . ucfirst($factory);

                if(class_exists($class_name, FALSE))
                {
                    $this->{$factory} = new $class_name();
                }
            }
        }

        return $this->{$factory};
    }
}