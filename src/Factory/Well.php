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

class Well extends Factory
{
    protected $_well = NULL;
    protected $_attributes
        = array(
            'class' => ['well']
        );

    public function build()
    {

        @list($jumbotron,$type) = func_get_args();

        $this->_jumbotron = $jumbotron;

        if(isset($type))
        {
            if($type === 'small')
            {
                $type = 'well-sm';
            }
            elseif($type === 'large')
            {
                $type = 'well-lg';
            }

            $this->add_class( $type );
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
            if(in_array($method, array('small','large')))
            {
                @list($well, $for, $type) = $args;

                return $this->create($well, $for, $method);
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
        if ( isset( $this->_jumbotron ) )
        {
            return (new Tag('div', $this->_jumbotron, $this->_attributes))->render();
        }

        return NULL;
    }
}