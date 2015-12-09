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
 * @package Label
 */
class Label extends Factory
{
	protected $_label = NULL;
	protected $_attributes
		= array(
			'class' => ['label'],
		);

    // ------------------------------------------------------------------------

    /**
     * build
     * @param string | array  $label
     * @param string $type
     * @return object
     */
	public function build()
	{
        @list($label, $for,$type) = func_get_args();

        if(is_array($label))
        {
            foreach ($label as $labels => $label_attributes)
            {

                if(isset($this->_attributes))
                {
                    unset($this->_attributes);
                    $this->_attributes['class'] = ['label'];
                }

                if(isset($label_attributes['for'])
                {
                    $this->_attributes['for'] = $label_attributes['for'];
                }

                if(isset($label_attributes['class']))
                {
                    $this->add_class('label-'.$label_attributes['class']);
                }

                if(isset($label_attributes['id']))
                {
                    $this->_attributes['id'] = $label_attributes['id'];
                }

                $array_label[$labels] = $this->_attributes;
            }

            $this->_label = $array_label;

        }
        else
        {
            $this->_label = $label;

            if(isset($for)
            {
                $this->_attributes['for'] = $for;
            }

            if(isset($type))
            {
                $this->add_class( 'label-' . $type );
            }
        }

        return $this;

	}

    // ------------------------------------------------------------------------

    /**
     * magic method __call
     * @param string | array $method
     * @param array $args
     * @return object | error exception
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
            $func = array('danger','primary','success','info','warning');

            if(in_array($method, $func))
            {
                @list($label,$for) = $args;

                return $this->build($label,$for,$method);
            }
            else
            {
                throw new Exception("Label::".$method."does not Exist!!", 1);
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
		if ( isset( $this->_label ) && is_array($this->_label))
        {
            foreach ($this->_label as $label => $attributes) {


                $output[] = (new Tag('span', $label, $attributes))->render();
            }

            return implode(PHP_EOL, $output);
        }
        else
		{
			return (new Tag('span', $this->_label, $this->_attributes))->render();
		}

		return NULL;
	}
}