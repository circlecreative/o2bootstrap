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

class Label extends Factory
{
	protected $_label = NULL;

	protected $_attributes
		= array(
			'class' => ['label'],
		);

	public function build()
	{

        @list($label, $type) = func_get_args();

		$this->_label = $label;

        if(isset($type))
        {
		    $this->add_class( 'label-' . $type );
        }

		return $this;
	}

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
                @list($label) = $args;

                return $this->create($label,$method);
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
		if ( isset( $this->_label ) )
		{
			return (new Tag('label', $this->_label, $this->_attributes))->render();
		}

		return NULL;
	}
}