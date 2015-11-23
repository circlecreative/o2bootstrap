<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 13:24
 *
 */

namespace O2System\Bootstrap\Factory;


use O2System\Bootstrap\Interfaces\Factory;
use O2System\Bootstrap\Factory\Tag;

class Addon extends Factory
{
    protected $_addon = NULL;
    protected $_append = NULL;
    protected $_prepend = NULL;

    protected $_attributes
        = array(
            'class' => ['input-group'],
        );

    public function append($content=NULL)
    {
        $this->_append = $content;

        return $this;
    }

    public function prepend($content=NULL)
    {
       $this->_prepend = $content;

        return $this;
    }

    public function build()
    {
        @list($addon,$type) = func_get_args();
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

        return $this;
    }

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
                @list($addon) = $args;

                return $this->build($addon, $method);
            }
            else
            {
                echo '<h1>'.$method.' Function is not Permitted </h1>';
                exit();
            }
        }
    }

    /**
     * Render
     *
     * @return null|string
     */
    public function render()
    {
        if ( isset( $this->_addon ) )
        {
            $div = new Tag('div',NULL,$this->_attributes);
            $output[] = $div->open();
            if(isset($this->_append))
            {
                $output[] = '<span class="input-group-addon">'.$this->_append.'</span>';
            }

            $output[] = $this->_addon;

            if(isset($this->_prepend))
            {
                $output[] = '<span class="input-group-addon">'.$this->_prepend.'</span>';
            }

            $output[] = $div->close();


            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}