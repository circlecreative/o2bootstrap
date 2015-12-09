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
 * Class Bootstrap Alert Builder
 * @package O2Boostrap\Factory
 */
class Alert extends Factory
{
    protected $_alert = NULL;
    protected $_dismissible = FALSE;
    protected $_title_string = NULL;
    protected $_title_tag = 'strong';
    protected $_icon = NULL;

    protected $_attributes
        = array(
            'class' => ['alert'],
            'role' => 'alert'
        );

    // ------------------------------------------------------------------------

    /**
     * Builder
     * @return object
     */
    public function build()
    {
        @list($alert,$type) = func_get_args();

        if(is_array($alert) && isset($alert['list']))
        {
            $this->_alert = $alert['list'];

            if(isset($type))
            {
                $this->add_class( 'alert-' . $type );
            }
        }
        elseif(is_array($alert))
        {
            foreach ($alert as $string => $attributes)
            {
                if(isset($this->_attributes))
                {
                    unset($this->_attributes);
                    $this->_attributes = [
                                            'class' => ['alert'],
                                            'role' => 'alert'
                                                ];
                }

                if(isset($type))
                {
                    $this->add_class( 'alert-' . $type );
                }

                if(isset($attributes['class']))
                {
                    $this->add_class('alert-'.$attributes['class']);
                }

                if(isset($attributes['id']))
                {
                    $this->set_id($attributes['id']);
                }

                if(isset($attributes['dismissible']))
                {
                    $this->dismissible();
                }

                if(isset($attributes['icon']))
                {
                    $this->icon($attributes['icon']);
                }

                $this->_alert = $string;
                $output[] = $this->render();

            }

            return implode(PHP_EOL, $output);
        }
        else
        {
            $this->_alert = $alert;

            if(isset($type))
            {
                $this->add_class( 'alert-' . $type );
            }
        }


        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Dismissible Alert
     * @return object
     */
    public function dismissible()
    {
        $this->_dismissible = TRUE;
        $this->add_class('alert-dismissible');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Alert Title
     * @param string $title
     * @param string $tag
     * @return object
     */
    public function title($title, $tag = 'strong')
    {
        $this->_title_string = $title;
        $this->_title_tag = $tag;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Alert Icon
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
     * Call Method
     * @param type $method
     * @param type $args
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
            $func = array('danger','default','primary','success','info','warning');

            if(in_array($method, $func))
            {
                @list($alert, $for) = $args;

                return $this->build($alert, $method);
            }
            else
            {
                throw new Exception("Alert::".$method."does not Exists!!", 1);
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
        if ( isset( $this->_alert ))
        {
            $div = new Tag('div',NULL,$this->_attributes);

            $output[] = $div->open();

            if($this->_dismissible===TRUE)
            {
                $output[] = (new Tag('button',
                    new Tag('span','&times;',['aria-hidden'=>'true'])
                    , [
                    'type' => 'button',
                    'class' => 'close',
                    'data-dismiss' => 'alert',
                    'aria-label' => 'Close'
                ]))->render();
            }


            if(isset($this->_title_string))
            {
                $title = '';

                if(isset($this->_icon))
                {
                    $title = (new Tag('i',NULL,['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
                }

                $title.= '&nbsp;'.$this->_title_string;

                $output[] = (new Tag($this->_title_tag,$title,array()))->render();
            }
            elseif(isset($this->_icon))
            {
                $output[] = (new Tag('i',NULL,['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
            }

            if(is_array($this->_alert))
            {
                $output[] = '<br />';
                $list = new Tag('ul', NULL,['class' => 'alert-list']);

                $output[] = $list->open();
                foreach($this->_alert as $_alert)
                {
                    $output[] = (new Tag('li', $_alert,array()))->render();
                }

                $output[] = $list->close();
            }
            else
            {
                $output[] = $this->_alert;
            }

            $output[] = $div->close();

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}