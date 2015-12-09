<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 11:57
 */

namespace O2System\Bootstrap\Factory;

use O2System\Bootstrap\Interfaces\Factory;

class Tag extends Factory
{
	protected static $_self_closing_tags = array(
		'area',
		'base',
		'br',
		'col',
		'command',
		'embed',
		'hr',
		'img',
		'input',
		'keygen',
		'link',
		'meta',
		'param',
		'source',
		'track',
		'wbr',
	);

	protected $_tag;
	protected $_content    = NULL;
	protected $_attributes = array();

	public function build()
	{
		@list( $tag, $content, $attributes ) = func_get_args();

		if ( isset( $tag ) )
		{
			$this->_tag = $tag;
		}

		if ( isset( $content ) )
		{
			if ( is_array( $content ) )
			{
				$attributes = $content;
			}
			elseif ( is_string( $content ) )
			{
				$this->content( $content );
			}
			elseif ( $content instanceof Tag )
			{
				$this->_content = $content;
			}
		}

		$this->_attributes = $attributes;

		return $this;
	}

	public function set_tag( $tag )
	{
		$this->_tag = $tag;
	}

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
		if ( ! isset( $this->_attributes[ 'class' ] ) )
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
		if ( ! isset( $this->_attributes[ 'class' ] ) )
		{
			$this->_attributes[ 'class' ] = array();
		}

		array_push( $this->_attributes[ 'class' ], $class );

		return $this;
	}

	public function set_attributes( array $attributes = array() )
	{
		if ( empty( $this->_attributes ) )
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

	public function open()
	{
		return '<' . $this->_tag . $this->_stringify_attributes( $this->_attributes ) . ( in_array( $this->_tag, self::$_self_closing_tags ) ? '/>' : '>' );
	}

	public function close()
	{
		return '</' . $this->_tag . '>';
	}

	public function content( $content )
	{
		$this->_content = trim( $content );
	}

	public function render()
	{
		if ( in_array( $this->_tag, self::$_self_closing_tags ) )
		{
			return $this->open();
		}
		else
		{
			$output[] = $this->open();

			if ( $this->_content instanceof Tag )
			{
				$output[] = $this->_content->render();
			}
			elseif ( empty( $this->_content ) )
			{
				$output[] = $this->close();
				return implode( '', $output );
			}
			else
			{
				$output[] = $this->_content;
				$output[] = $this->close();

				return implode( PHP_EOL, $output );
			}
		}
	}
}