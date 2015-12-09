<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 11:29
 */

namespace O2System\Bootstrap\Interfaces;


use O2System\Glob\Interfaces;

abstract class Factory
{
    /**
     * HTML Output Attributes
     *
     * @access  protected
     * @type    string
     */
    protected $_attributes;

    final public function __construct()
    {
        return call_user_func_array(array($this, 'build'), func_get_args());
    }

    final public function create()
    {
        return call_user_func_array(array($this, 'build'), func_get_args());
    }

    abstract public function build();

    /*public function factory()
    {
        $class = get_called_class();
        return new $class($this->library);
    }*/

    /**
     * Set HTML ID
     *
     * @param   string $id
     *
     * @access  public
     * @return  $this
     */
    public function set_id( $id )
    {
        $this->add_attribute( 'id', $id );

        return $this;
    }

    public function set_classes( array $classes = array() )
    {
        if( ! isset( $this->_attributes[ 'class' ] ) )
        {
            $this->_attributes[ 'class' ] = $classes;
        }
        else
        {
            $this->_attributes[ 'class' ] = array_merge( $this->_attributes[ 'class' ], $classes );
        }

        return $this;
    }

    public function add_class( $class )
    {
        if( ! isset( $this->_attributes[ 'class' ] ) )
        {
            $this->_attributes[ 'class' ] = array();
        }

        array_push( $this->_attributes[ 'class' ], $class );

        return $this;
    }

    public function set_attributes( array $attributes = array() )
    {
        if( empty( $this->_attributes ))
        {
            $this->_attributes = $attributes;
        }
        else
        {
            $this->_attributes = array_merge( $this->_attributes, $attributes );
        }

        return $this;
    }

    public function add_attribute( $name, $value )
    {
        $this->_attributes[ $name ] = $value;
    }

    protected function _stringify_attributes( array $attributes = array() )
    {
        $attributes = empty( $attributes ) ? $this->_attributes : $attributes;

        if( is_object( $attributes ) && count( $attributes ) > 0 )
        {
            $attributes = (array)$attributes;
        }

        if( is_array( $attributes ) )
        {
            $attr = '';

            if( count( $attributes ) === 0 )
            {
                return $attr;
            }

            foreach( $attributes as $key => $value )
            {
                if( $key === 'class' AND is_array($value) )
                {
                    $value = implode( ' ', $value );
                }

                if(is_array($value))
                {
                    $value = implode(', ', $value);
                }

                if(is_bool($value))
                {
                    $value = $value === TRUE ? 'true' : 'false';
                }

                if( $key === 'js' )
                {
                    $attr .= $key . '=' . $value . ',';
                }
                else
                {
                    $attr .= ' ' . $key . '="' . $value . '"';
                }
            }

            return rtrim( $attr, ',' );
        }
        elseif( is_string( $attributes ) && strlen( $attributes ) > 0 )
        {
            return ' ' . $attributes;
        }

        return $attributes;
    }

    abstract public function render();
}