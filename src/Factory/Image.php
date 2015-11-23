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

class Image extends Factory
{
    protected $_image = NULL;
    protected $_attributes
        = array(
            'class' => ['img']
        );

    public function size($width=NULL,$height=NULL)
    {
    	$this->_attributes['width'] = $width;
    	$this->_attributes['height'] = $height;

    	return $this;
    }

    public function alt($alt=NULL)
    {
    	$this->_attributes['alt'] = $alt;

    	return $this;
    }


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
        if ( isset( $this->_attributes['src'] ) )
        {
            return (new Tag('img', NULL, $this->_attributes))->render();
        }

        return NULL;
    }
}