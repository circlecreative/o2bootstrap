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
 * Addon
 * @package O2Bootstrap\Factory
 */
class Addon extends Factory
{
    protected $_addon = NULL;
    protected $_append = NULL;
    protected $_prepend = NULL;
    protected $_label = NULL;
    protected $_attributes
        = array(
            'class' => ['input-group'],
        );

    // ------------------------------------------------------------------------

    /**
     * append function
     * @param string $content
     * @return object
     */
    public function append($content=NULL)
    {
        $this->_append = $content;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * prepend function for
     * @param type $content
     * @return object
     */
    public function prepend($content=NULL)
    {
       $this->_prepend = $content;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * build html bootstrap
     * @return object
     */
    public function build()
    {
        @list($addon,$label,$type) = func_get_args();
        $this->_addon = $addon;

        if(isset($type))
        {
            if($type==='large')
            {
                $type = 'lg';
            }
            elseif($type==='small')
            {
                $type = 'sm';
            }

            $this->add_class( 'input-group-' .$type );
        }

        if(isset($label))
        {
            $this->_label = $label;
        }

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Call function method
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
            $func = array('large','small','default');

            if(in_array($method, $func))
            {
                @list($addon,$label) = $args;

                return $this->build($addon, $label, $method);
            }
            else
            {
                throw new Exception("Addon::".$method."does not Exists!!", 1);
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
        if ( isset( $this->_addon ) )
        {
            if(isset($this->_label))
            {
                $output[] = (new Tag('label',$this->_label,[]))->render();
            }

            $div = new Tag('div',NULL,$this->_attributes);
            $output[] = $div->open();
            if(isset($this->_append))
            {
                $output[] = (new Tag('span',$this->_append,['class'=>['input-group-addon']]))->render();
            }

            $output[] = $this->_addon;

            if(isset($this->_prepend))
            {
                $output[] = (new Tag('span',$this->_prepend,['class'=>['input-group-addon']]))->render();
            }

            $output[] = $div->close();

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}