<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 13:24
 */

namespace O2System\Bootstrap\Factory;


use O2System\Bootstrap\Interfaces\Factory;

class Nav extends Factory
{
    protected $_attributes = array(
        'class' => array(
            'nav'
        )
    );
    protected $_menus      = array();

    /**
     * Add Tab Menu
     *
     * @param   string $label
     * @param   string|array $link
     * @param   array   $attributes
     *
     * @access  public
     * @return  $this
     */
    public function add_menu( $label, $link, array $attributes = array() )
    {
        $menu[ 'label' ] = $label;

        if( is_string( $link ) )
        {
            $menu[ 'link' ] = [ 'href' => $link ];
        }
        elseif($link instanceof Nav)
        {
            $menu[ 'link' ] = $link;
        }
        else
        {
            if( is_array( $link ) OR is_object( $link ) )
            {
                $menu[ 'link' ] = (array) $link;
            }
        }

        if(empty($attributes))
        {
            $attributes = ['role' =>'presentation'];
        }
        elseif(!isset($attributes['role']))
        {
            $attributes['role'] = 'presentation';
        }

        $menu[ 'attributes' ] = $attributes;

        $this->_menus[] = $menu;

        return $this;
    }

    public function stacked()
    {
        $this->_attributes[ 'class' ][] = 'nav-stacked';

        return $this;
    }

    public function justified()
    {
        $this->_attributes[ 'class' ][] = 'nav-justified';

        return $this;
    }

    public function build()
    {
        @list($menus) = func_get_args();

        $menus = empty($menus) ? $this->_menus : $menus;
        $output = array();
        $has_active = FALSE;

        if( ! empty( $menus ) )
        {
            $output[] = '<ul' . $this->_stringify_attributes() . '>';

            foreach( $menus as $menu )
            {
                if(isset($menu['attributes']['class']))
                {
                    if(is_string($menu['attributes']['class']))
                    {
                        if(strpos($menu['attributes']['class'], 'active') !== FALSE)
                        {
                            $has_active = TRUE;
                            break;
                        }
                    }
                    elseif(is_array($menu['attributes']['class']))
                    {
                        if(in_array( 'active', $menu['attributes']['class']) !== FALSE)
                        {
                            $has_active = TRUE;
                            break;
                        }
                    }
                }
            }

            if($has_active === FALSE)
            {
                $menus[0]['attributes']['class'][] = 'active';
            }

            foreach( $menus as $menu )
            {
                $output[] = '<li' . $this->_stringify_attributes( $menu[ 'attributes' ] ) . '>';

                if(empty($menu['link']))
                {
                    $output[] = $menu['label'];
                }
                elseif($menu['link'] instanceof Nav)
                {
                    $attributes['class'] = 'dropdown-toggle';
                    $attributes['data-toggle'] = 'dropdown';
                    $attributes['href'] = '#';
                    $attributes['role'] = 'button';
                    $attributes['aria-haspopup'] = 'true';
                    $attributes['aria-expanded'] = 'false';

                    $output[] = '<a'.$this->_stringify_attributes($attributes).'>'.$menu['label'].'<span class="caret"></span></a>';
                    $output[] = $menu['link']->render();
                }
                else
                {
                    $output[] = '<a'.$this->_stringify_attributes($menu['link']).'>'.$menu['label'].'</a>';
                }

                $output[] = '</li>';
            }
            $output[] = '</ul>';
        }

        return implode(PHP_EOL, $output);
    }
}