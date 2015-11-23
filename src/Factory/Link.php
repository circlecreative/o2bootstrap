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

class Link extends Factory
{
    protected $_link = NULL;
    protected $_icon   = NULL;
    protected $_dropdown = NULL;
    protected $_href = NULL;
    protected $_attributes
        = array(
            'class' => ['btn']
        );

    public function dropdown(array $dropdown=array())
    {
        $this->_dropdown = $dropdown;
        $this->add_class('dropdown-toggle');
        $this->_attributes['data-toggle'] = "dropdown";
        return $this;
    }

    public function icon($icon)
    {
        $this->_icon = $icon;

        return $this;
    }

    public function tiny()
    {
        $this->add_class('btn-xs');

        return $this;
    }

    public function small()
    {
        $this->add_class('btn-sm');

        return $this;
    }

    public function medium()
    {
        $this->add_class('btn-md');

        return $this;
    }

    public function large()
    {
        $this->add_class('btn-lg');

        return $this;
    }

    public function block()
    {
        $this->add_class('btn-block');

        return $this;
    }

    public function active()
    {
        $this->add_class('active');

        return $this;
    }


    public function disable()
    {
        $this->add_class('disabled');

        return $this;
    }

    public function build()
    {
        @list($label, $href, $style) = func_get_args();
        $this->_link = $label;
        if(isset($href))
        {
            $this->_attributes['href'] = $href;
        }

        if(isset($style))
        {
            $this->add_class( 'btn-' . $style );
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
            $func = array('danger','default','primary','success','info','warning','link');

            if(in_array($method, $func))
            {
                @list($link_label, $link_href) = $args;
                return $this->build($link_label,$link_href,$method);
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
        if ( isset( $this->_link ) )
        {
            $link = new Tag('a', NULL, $this->_attributes);

            if(isset($this->_dropdown))
            {
                $output[] = '<div class="dropdown">';
            }

            $output[] = $link->open();

            if(isset($this->_icon))
            {
                $output[] = (new Tag('span',['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
            }

            $output[] = $this->_link;


            if(isset($this->_dropdown))
            {
                $output[] = '<span class="caret"></span>';
            }

            $output[] = $link->close();

            if(isset($this->_dropdown))
            {
                $drop[] = '<ul class="dropdown-menu">';
                foreach ($this->_dropdown as $key => $value) {

                    $a = (new Tag('a',$value['name'],['href'=>$value['link']]))->render();
                    $drop[] = (new Tag('li',$a))->render();
                }
                $drop[] = '</ul>';

                $output[] = implode( PHP_EOL, $drop );
                $output[] = '</div>';
            }

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}
