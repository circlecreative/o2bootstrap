<?php
/**
 * YukBisnis.com
 *
 * Application Engine under O2System Framework for PHP 5.4 or newer
 *
 * This content is released under PT. Yuk Bisnis Indonesia License
 *
 * Copyright (c) 2015, PT. Yuk Bisnis Indonesia.
 *
 * @package        Applications
 * @author         Aradea
 * @copyright      Copyright (c) 2015, PT. Yuk Bisnis Indonesia.
 * @since          Version 2.0.0
 * @filesource
 */

// ------------------------------------------------------------------------

namespace O2System\Bootstrap\Factory;

use O2System\Bootstrap\Interfaces\Factory;
use O2System\Bootstrap\Factory\Tag;

/**
 *
 * @package well
 */
class Well extends Factory
{
    protected $_well = NULL;
    protected $_attributes
        = array(
            'class' => ['well']
        );

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object
     */
    public function build()
    {

        @list($jumbotron,$type) = func_get_args();

        $this->_jumbotron = $jumbotron;

        if(isset($type))
        {
            if($type === 'small')
            {
                $type = 'well-sm';
            }
            elseif($type === 'large')
            {
                $type = 'well-lg';
            }

            $this->add_class( $type );
        }

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * __call meagic method
     * @param string $method
     * @param array $args
     * @return object
     */
    public function __call($method, $args = array())
    {

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
            if(in_array($method, array('small','large')))
            {
                @list($well, $for, $type) = $args;

                return $this->build($well, $for, $method);
            }
            else
            {
                throw new Exception("Well::".$method."does not Exists!!", 1);

            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Render
     *
     * @return null|string
     */
    public function render()
    {
        if ( isset( $this->_jumbotron ) )
        {
            return (new Tag('div', $this->_jumbotron, $this->_attributes))->render();
        }

        return NULL;
    }
}