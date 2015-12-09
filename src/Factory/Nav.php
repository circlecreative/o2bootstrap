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

/**
 *
 * @package Nav
 */
class Nav extends Factory
{
	protected $_attributes = array(
		'class' => array(
			'nav',
		),
	);
	protected $_menus      = array();

	// ------------------------------------------------------------------------

	/**
	 * Add Tab Menu
	 *
	 * @param   string       $label
	 * @param   string|array $link
	 * @param   array        $attr
	 *
	 * @access  public
	 * @return  $this
	 */
	public function add_menu( $label, $link, array $attr = array() )
	{
		if ( is_array( $label ) )
		{
			foreach ( $label as $key => $value )
			{
				$$key = $value;
			}
		}

		$menu[ 'label' ] = $label;

		if ( is_string( $link ) )
		{
			$menu[ 'link' ] = [ 'href' => $link ];
		}
		elseif ( $link instanceof Nav )
		{
			$menu[ 'link' ] = $link;
		}
		else
		{
			if ( is_array( $link ) )
			{
				if ( isset( $link[ 'attr' ] ) )
				{
					$link_attr = $link[ 'attr' ];
					unset( $link[ 'attr' ] );

					$menu[ 'link' ] = array_merge( $link, $link_attr );
					unset($link_attr);
				}
				else
				{
					$menu[ 'link' ] = $link;
				}
			}
		}

		if ( empty( $attr ) )
		{
			$attr = [ 'role' => 'presentation' ];
		}
		elseif ( ! isset( $attr[ 'role' ] ) )
		{
			$attr[ 'role' ] = 'presentation';
		}

		$menu[ 'attr' ] = $attr;

		$this->_menus[] = $menu;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * stacked
	 *
	 * @return object
	 */
	public function stacked()
	{
		$this->_attributes[ 'class' ][] = 'nav-stacked';

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * justified
	 *
	 * @return object
	 */
	public function justified()
	{
		$this->_attributes[ 'class' ][] = 'nav-justified';

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * build
	 */
	public function build()
	{
		@list( $nav, $type ) = func_get_args();
		$this->add_class( 'navbar-nav' );
		$this->_nav = $nav;

		return $this;
	}

	// ------------------------------------------------------------------------

	/**
	 * render
	 *
	 * @return string
	 */
	public function render()
	{

		if ( isset( $this->_nav ) )
		{
			$navbar = new Tag( 'ul', NULL, $this->_attributes );

			$output[] = $navbar->open();

			foreach ( $this->_nav as $name => $attributes )
			{

				if ( isset( $attributes[ 'child' ] ) )
				{
					$child[] = '<ul class="dropdown-menu">';
					foreach ( $nav[ 'child' ] as $childname => $childattributes )
					{

						$a = ( new Tag( 'a', $childname, $childattributes ) )->render();
						$child[] = ( new Tag( 'li', $a, [ ] ) )->render();
					}

					$child[] = '</ul>';

					$child_string = implode( PHP_EOL, $child );
					$caret = '<span class="caret"></span>';
					$a = ( new Tag( 'a', $name . $caret, [ 'class' => "dropdown-toggle", 'data-toggle' => "dropdown", 'href' => "#" ] ) )->render();

					$a = $a . $child_string;
					$drop_attr = 'dropdown';
				}
				else
				{
					$a = ( new Tag( 'a', $name, $attributes ) )->render();
					$drop_attr = '';
				}

				$output[] = ( new Tag( 'li', $a, [ 'class' => [ $drop_attr ] ] ) )->render();
			}

			$output[] = $navbar->close();

			return implode( PHP_EOL, $output );
		}

		$menus = empty( $menus ) ? $this->_menus : $menus;
		$output = array();
		$has_active = FALSE;

		if ( ! empty( $menus ) )
		{
			$ul = new Tag( 'ul', $this->_attributes );
			$output[] = $ul->open();

			foreach ( $menus as $menu )
			{
				if ( isset( $menu[ 'attributes' ][ 'class' ] ) )
				{
					if ( is_string( $menu[ 'attributes' ][ 'class' ] ) )
					{
						if ( strpos( $menu[ 'attributes' ][ 'class' ], 'active' ) !== FALSE )
						{
							$has_active = TRUE;
							break;
						}
					}
					elseif ( is_array( $menu[ 'attributes' ][ 'class' ] ) )
					{
						if ( in_array( 'active', $menu[ 'attributes' ][ 'class' ] ) !== FALSE )
						{
							$has_active = TRUE;
							break;
						}
					}
				}
			}

			if ( $has_active === FALSE )
			{
				$menus[ 0 ][ 'attributes' ][ 'class' ][] = 'active';
			}

			foreach ( $menus as $menu )
			{
				$li = new Tag( 'li', $menu[ 'attr' ] );
				$output[] = $li->open();

				if ( empty( $menu[ 'link' ] ) )
				{
					$output[] = $menu[ 'label' ];
				}
				elseif ( $menu[ 'link' ] instanceof Nav )
				{
					$attributes[ 'class' ] = 'dropdown-toggle';
					$attributes[ 'data-toggle' ] = 'dropdown';
					$attributes[ 'href' ] = '#';
					$attributes[ 'role' ] = 'button';
					$attributes[ 'aria-haspopup' ] = 'true';
					$attributes[ 'aria-expanded' ] = 'false';

					$output[] = ( new Tag( 'a', $menu[ 'label' ] . ( new Tag( 'span', [ 'class' => [ 'caret' ] ] ) )->render(), $attributes ) )->render();
					$output[] = $menu[ 'link' ]->render();
				}
				else
				{
					$output[] = ( new Tag( 'a', $menu[ 'label' ], $menu[ 'link' ], $menu[ 'attr' ] ) )->render();
				}

				$output[] = $li->close();
			}
			$output[] = $ul->close();
		}

		return implode( PHP_EOL, $output );
	}
}