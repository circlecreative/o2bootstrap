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
use O2System\Bootstrap\Factory\Button;

/**
 * Class Bootstrap Alert Builder
 * @package O2Boostrap\Factory
 */
class Button_group extends Factory
{
    protected $_attributes = NULL;
    protected $_btn_group_attributes = ['class'=>['btn_group']];

    /**
     * Group
     * @param string | array $button
     * @param type $position
     * @return type
     */
    public function build()
    {
        @list($button,$position) = func_get_args();
        if(isset($position) && $position==='right')
        {
            array_push($this->_btn_group_attributes['class'],'pull-right');
        }
        elseif(isset($position) && $position==='left')
        {
            array_push($this->_btn_group_attributes['class'],'pull-left');
        }

        if(isset($button) && is_array($button))
        {
            foreach ($button as $string => $attributes)
            {
                unset($this->_attributes);

                $this->_attributes = array('class'=>['btn_group'],'role'=>'group');
                if(isset($attributes['class']))
                {
                    $this->add_class($attributes['class']);
                }

                if(isset($attributes['id']))
                {
                    $this->set_id($attributes['id']);
                }

                if(isset($attributes['type']))
                {
                    $this->_attributes['type'] = $attributes['type'];
                }

                $output[] = $this->render();
            }

            $btn_group = new Tag('div',NULL,$this->_btn_group_attributes);
            $render[] = $btn_group->open();
            $render[] = implode(PHP_EOL, $output);
            $render[] = $btn_group->close();

            return implode(PHP_EOL, $render);
        }
        else
        {
            $btn_group = new Tag('div',NULL,$this->_btn_group_attributes);
            $render[] = $btn_group->open();
            $render[] = $button;
            $render[] = $btn_group->close();

            return implode(PHP_EOL, $render);
        }

        return NULL;
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

    public function render()
    {
        $new_button = new Button();
        return $new_button->render();
    }
}