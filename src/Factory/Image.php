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
 * @package Image
 */
class Image extends Factory
{
    protected $_image = NULL;
    protected $_attributes= array(
            'class' => ['img']
        );

    // ------------------------------------------------------------------------

    /**
     * size
     * @param string $width
     * @param string $height
     * @return object
     */
    public function size($width=NULL,$height=NULL)
    {
    	$this->_attributes['width'] = $width;
    	$this->_attributes['height'] = $height;

    	return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * alt
     * @param string $alt
     * @return object
     */
    public function alt($alt=NULL)
    {
    	$this->_attributes['alt'] = $alt;

    	return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object
     */
    public function build()
    {
        @list($image, $type) = func_get_args();

        $this->_attributes['src'] = $image;

        if(isset($type))
        {
            $this->add_class( 'img-' . $type );
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

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
        	if(in_array($method, array('circle','responsive','thumbnail')))
        	{
        		@list($image, $type) = $args;

            	return $this->build($image,$method);
        	}
        	else
        	{
        		throw new Exception("Image::".$method."does not Exists!!", 1);

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
        if ( isset( $this->_attributes['src'] ) )
        {
            return (new Tag('img', NULL, $this->_attributes))->render();
        }

        return NULL;
    }
}