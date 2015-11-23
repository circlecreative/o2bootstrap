<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 13:24
 *
 */

namespace O2System\Bootstrap\Drivers;


use O2System\Bootstrap\Interfaces\Driver;

class Thumbnail extends Driver
{
	protected $_thumbs     = array();
	protected $_mobile_rows = 1;

	public function set_mobile_rows($rows)
	{
		$this->_mobile_rows = (int) $rows;
	}

	/**
	 * Add Badge
	 *
	 * @param   string       $label
	 * @param   string|array $link
	 * @param   array        $attributes
	 *
	 * @access  public
	 * @return  $this
	 */
	public function add_menu( $label = NULL, $attr_class, array $attributes = array() )
	{
		$menu[ 'label' ] = $label;

		$menu[ 'attributes' ] = array();

		if ( ! empty( $attr_class ) )
		{
			$menu[ 'attributes' ] = array_merge( $menu[ 'attributes' ], $attr_class );
		}

		$this->_menus[] = $menu;

		return $this;
	}

	public function add_thumb( $thumb, array $attributes = array() )
	{
		$this->_thumbs[$thumb] = array(
			'src' => $thumb
		);
	}

	public function add_thumbs( array $thumbs )
	{
		foreach ( $thumbs as $thumb )
		{
			$this->add_thumb( $thumb );
		}

		return $this;
	}

	public function render()
	{
		// Set Mobile Class
		$attribute['class'][] = 'col-xs-'.$this->_mobile_rows;

		// Set Desktop Class
		$attribute['class'][] = 'col-lg-'.$this->_desktop_rows;

		$output[] = '<div class="row">';

			$output[] = '<div' . $this->_stringify_attributes( $attribute ) . '>';

			foreach($this->_thumbs as $thumb_src => $thumb_property)
			{
				if(array_key_exists('caption', $thumb_property))
				{

				}
			}
			$output[] = '</div>';
		$output[] = '</div>';
	}
}