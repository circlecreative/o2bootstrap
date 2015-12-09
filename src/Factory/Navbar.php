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
 * @package Navbar
 */
class Navbar extends Factory
{
    protected $_attributes = array('class'=>array('navbar'));
    protected $_navbar;
    protected $_brand;
    protected $_brand_attributes;
    protected $_menu_position;

    /**
     * brand
     * @param string $brand
     * @param string $link
     * @return object
     */
    public function brand($brand=NULL,$link='#')
    {
        $this->_brand = $brand;
        $this->_brand_attributes = array('class'=>'navbar-brand','href'=>$link);

        return $this;
    }

    /**
     * top
     * @return object
     */
    public function top()
    {
        $this->add_class('navbar-fixed-top');

        return $this;
    }

    /**
     * bottom
     * @return type
     */
    public function bottom()
    {
        $this->add_class('navbar-fixed-bottom');

        return $this;
    }

    /**
     * build
     * @return type
     */
    public function build()
    {
        @list($navbar, $position,$type) = func_get_args();

        $this->_navbar = $navbar;

        if(isset($type))
        {
            $this->add_class( 'navbar-'. $type);
        }

        if(isset($position))
        {
            $this->_menu_position= 'pull-'.$position;
        }

        return $this;
    }


    /**
     * __call magic method
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
            $func = array('default','inverse');

            if(in_array($method, $func))
            {
                @list($navbar,$position) = $args;
                return $this->build($navbar,$position,$method);
            }
            else
            {
                throw new Exception("Navbar::".$method."does not Exists!!", 1);
            }
        }
    }

    /**
     * render
     * @return object
     */
    public function render()
    {
        if(isset($this->_navbar))
        {
            $div = new Tag('div',NULL,$this->_attributes);

            $output[] = $div->open();

            $container = new Tag('div',['class'=>['container-fluid']]);
            $output[] = $container->open();

            if(isset($this->_brand))
            {
                $header = new Tag('div',['class'=>['navbar-header']]);
                $output[] = $header->open();
                $output[] = (new Tag('a',$this->_brand,$this->_brand_attributes))->render();
                $output[] =  $header->close();
            }

            $pos = (isset($this->_menu_position)) ? $this->_menu_position : '';
            $ul = new Tag('ul',['class'=>['nav','navbar-nav', $pos]]);
            $output[] = $ul->open();

            foreach ($this->_navbar as $name => $attributes)
            {
                $active = (isset($attributes['active'])) ? 'active' : '';

                if(isset($attributes['child']))
                {
                    $ulmenu = new Tag('ul',['class'=>['dropdown-menu']]);
                    $child[] = $ulmenu->open();
                    foreach ($attributes['child'] as $childlabel => $childattributes)
                    {
                        $a = (new Tag('a',$childlabel,$childattributes))->render();
                        $child[] = (new Tag('li',$a,['class'=>'']))->render();
                    }

                    $child[] = $ulmenu->close();

                    $child_string = implode( PHP_EOL, $child );

                    $caret = (new Tag('span',['class'=>['caret']]))->render();
                    $a = (new Tag('a',$name.$caret,[ 'class'=>"dropdown-toggle",'data-toggle'=>"dropdown",'href'=>"#"]))->render();

                    $a = $a.$child_string;
                    $drop_attr = 'dropdown';
                }
                else
                {
                    $a = (new Tag('a',$name,$attributes))->render();
                    $drop_attr = '';
                }

                $output[] = (new Tag('li',$a,['class'=>[$active,$drop_attr]]))->render();
            }

            $output[] = $ul->close();
            $output[] = $container->close();
            $output[] = $div->close();

            return implode( PHP_EOL, $output );
        }

            $output[] = $navbar->close();

            return implode( PHP_EOL, $output );
    }
}
