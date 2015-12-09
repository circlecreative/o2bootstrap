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
 * @package progress
 */
class Progress extends Factory
{
    protected $_progress = NULL;
    protected $_active = NULL;

    protected $_attributes
        = array(
            'class' => ['progress-bar'],
            'role' => 'progressbar',
            'aria-valuenow'=> 0,
            'aria-valuemin'=> 0,
            'aria-valuemax'=>100,
            'style'=> "width:0%"
        );

    // ------------------------------------------------------------------------

    /**
     * stripped
     * @param string $active
     * @return object
     */
    public function striped($active=NULL)
    {
        $this->add_class('progress-bar-striped');

        if(isset($active) && $active==1)
        {
            $this->add_class('active');
        }

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object
     */
    public function build( )
    {
        @list($progress,$type) = func_get_args();

        $this->_attributes['aria-valuenow'] = $progress;
        $this->_attributes['style'] = "width:".$progress."%;" ;


        $this->_progress = $progress;

        if(isset($type))
        {
            $this->add_class( 'progress-bar-' . $type );
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
            $func = array('danger','success','info','warning');

            if(in_array($method, $func))
            {
                @list($progress) = $args;
                return $this->build($progress,$method);
            }
            else
            {
                throw new Exception("Progress::".$method."does not Exists!!", 1);
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
        if ( isset( $this->_progress ) )
        {
            $div = new Tag('div',NULL,['class'=>['progress']]);
            $output[] = $div->open();
            $value = $this->_progress.'% Complete';
            $output[] = (new Tag('div',$value,$this->_attributes))->render();
            $output[] = $div->close();

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}