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

class Badge extends Factory
{
    protected $_badge = NULL;

    protected $_attributes
        = array(
            'class' => ['badge'],
        );

    public function build()
    {

        @list($badge,$type) = func_get_args();

        $this->_badge = $badge;

        if(isset($type))
        {
            $this->add_class( 'badge-' . $type );
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
                @list($badge) = $args;

                return $this->build($badge,$method);
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
        if ( isset( $this->_badge ) )
        {
            return (new Tag('span', $this->_badge, $this->_attributes))->render();
        }

        return NULL;
    }
}