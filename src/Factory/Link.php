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
 * @package Link
 */
class Link extends Factory
{
    protected $_link = NULL;
    protected $_icon   = NULL;
    protected $_dropdown = NULL;
    protected $_href = NULL;
    protected $_attributes
        = array(
            'class' => ['btn']
        );

    // ------------------------------------------------------------------------

    /**
     * dropdown
     * @param array $dropdown
     * @return object
     */
    public function dropdown(array $dropdown=array())
    {
        $this->_dropdown = $dropdown;
        $this->add_class('dropdown-toggle');
        $this->_attributes['data-toggle'] = "dropdown";
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * icon
     * @param string $icon
     * @return object
     */
    public function icon($icon)
    {
        $this->_icon = $icon;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * tiny
     * @return object
     */
    public function tiny()
    {
        $this->add_class('btn-xs');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * small
     * @return type
     */
    public function small()
    {
        $this->add_class('btn-sm');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * medium
     * @return object
     */
    public function medium()
    {
        $this->add_class('btn-md');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * large
     * @return object
     */
    public function large()
    {
        $this->add_class('btn-lg');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * block
     * @return object
     */
    public function block()
    {
        $this->add_class('btn-block');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * active
     * @return object
     */
    public function active()
    {
        $this->add_class('active');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * disabled
     * @return object
     */
    public function disable()
    {
        $this->add_class('disabled');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object | string
     */
    public function build()
    {
        @list($label, $href, $style) = func_get_args();

        if(is_array($label))
        {
            foreach ($label as $string => $attributes)
            {
                if(isset($this->_attributes))
                {
                    unset($this->_attributes);
                    $this->_attributes = ['class'=>['btn']];
                }

                if(isset($attributes['href']))
                {
                    $this->_attributes['href'] = $attributes['href'];
                }

                if(isset($attributes['class']))
                {
                    $this->add_class( 'btn-' . $attributes['class'] );
                }

                if(isset($attributes['id']))
                {
                    $this->set_id($attributes['id']);
                }

                if(isset($attributes['child']))
                {
                    $this->dropdown($attributes['child']);
                    unset($attributes['child']);
                }

                $this->_link = $string;

                $output[] = $this->render();
            }

            return implode(PHP_EOL,$output);
        }

        $this->_link = $label;

        if(isset($href))
        {
            $this->_attributes['href'] = $href;
        }

        if(isset($style))
        {
            $this->add_class( 'btn-' . $style );
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
        $method = $method === 'error' ? 'danger' : $method;

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
            $func = array('danger','default','primary','success','info','warning','link');

            if(in_array($method, $func))
            {
                @list($link_label, $link_href) = $args;
                return $this->build($link_label,$link_href,$method);
            }
            else
            {
                throw new Exception("Link::".$method."does not Exists!!", 1);
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
        if ( isset( $this->_link ) )
        {
            $link = new Tag('a', NULL, $this->_attributes);

            if(isset($this->_dropdown))
            {
                $div = new Tag('div',NULL,['class'=>'dropdown']);
                $output[] = $div->open();
            }

            $output[] = $link->open();

            if(isset($this->_icon))
            {
                $output[] = (new Tag('span',['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
            }

            $output[] = $this->_link;


            if(isset($this->_dropdown))
            {
                $caret = (new Tag('span',NULL,['class'=>'caret']))->render();
            }

            $output[] = $link->close();


            if(isset($this->_dropdown))
            {
                $wrap = new Tag('ul',NULL,['class'=>'dropdown-menu']);
                $drop[] = $wrap->open();
                foreach ($this->_dropdown as $name => $attributes) {
                    $a = (new Tag('a',$name,$attributes))->render();
                    $drop[] = (new Tag('li',$a,array()))->render();
                }
                $drop[] =  $wrap->close();

                $output[] = implode( PHP_EOL, $drop );
                $output[] = $div->close();

                unset($this->_dropdown);
            }

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}
