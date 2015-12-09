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
 * @package Tabs
 */
class Tabs extends Factory
{
    protected $_attributes = array(
        'class' => ['nav'],
        'role' => 'tab'
    );
    protected $_nav = NULL;
    protected $_active = NULL;

    // ------------------------------------------------------------------------

    /**
     * stacked
     * @param string $attr
     * @return object
     */
    public function stacked()
    {
        $this->add_class('nav-stacked');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * justified
     * @param string $attr
     * @return object
     */
    public function justified()
    {
        $this->add_class('nav-justified');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object
     */
    public function build( )
    {
        @list($nav,$type) = func_get_args();

        if(is_string($nav))
        {
            $nav = array($nav);
        }

        $this->_nav = $nav;

        if ( isset( $for ) )
        {
            if(! isset($this->_attributes['id']))
            {
                $this->set_id( 'nav-' . $for );
            }
        }

        if(isset($type))
        {
            $this->add_class( 'nav-' . $type);
        }

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * __call magic method
     * @param string $method
     * @param array $args
     * @return type
     */
    public function __call($method, $args = array())
    {
        $method = $method === 'standard' ? 'tabs' : $method;

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
            $func = array('tabs','pills');

            if(in_array($method, $func))
            {
                @list($nav) = $args;

                return $this->build($nav, $method);
            }
            else
            {
                throw new Exception("Tabs::".$method."does not Exists!!", 1);

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
        if ( isset( $this->_nav ) )
        {
            //--- link tab ---\\
            $ul = new Tag('ul', NULL,$this->_attributes);

            //ul open  tag
            $output[] = $ul->open();

            foreach ($this->_nav as $label => $attributes) {

                if(isset($attributes['active']))
                {
                    $li = new Tag('li',NULL,['class'=>'active']);
                }
                else
                {
                    $li = new Tag('li',NULL,[]);
                }


                $output[] =  $li->open();

                //a open tag
                $a = new Tag('a',['href'=>'#'.$label,'role'=>'tab','data-toggle'=>'tab']);
                $output[] = $a->open();

                //for icon
                if(isset($nav['icon']))
                {
                    $output[] = (new Tag('i',['class'=>$attributes['icon']]))->render();
                }

                $output[] = $label;
                //a close tag
                $output[] = $a->close();

                $output[] = $li->close();


                //for content
                $active = (isset($attributes['active'])) ? 'active in' : '';
                $div = new Tag('div',['class'=>['tab-pane','fade',$active],'id'=>$label]);

                $content[] = $div->open();

                $content[] = $attributes['content'];

                $content[] = $div->close();


            }

            $output[] = $ul->close();

            //--- end link tab ---\\

            //--- content tab ---\\

            $divcontent = new Tag('div',['class'=>'tab-content']);

            $output[] = $divcontent->open();

            $output[] = implode( PHP_EOL, $content );

            $output[] = $divcontent->close();
            //--- end content tab ---\\

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}