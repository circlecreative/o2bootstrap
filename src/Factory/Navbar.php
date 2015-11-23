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

class Navbar extends Factory
{
    protected $_attributes = array('class'=>array('navbar'));
    protected $_navbar;

    /**
     * Add Label
     *
     * @param   string $label
     * @param   string|array $link
     * @param   array   $attributes
     *
     * @access  public
     * @return  $this
     */

    public function brand($brand=NULL,$link='#')
    {
        $this->_brand = $brand;
        $this->_brandattr = array('class'=>'navbar-brand','href'=>$link);

        return $this;
    }

    public function top()
    {
        $this->add_class('navbar-fixed-top');

        return $this;
    }

    public function bottom()
    {
        $this->add_class('navbar-fixed-bottom');

        return $this;
    }

    public function build()
    {
        @list($navbar, $type) = func_get_args();

        $this->_navbar = $navbar;

        if(isset($type))
        {
            $this->add_class( 'navbar-'. $type);
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
            $func = array('default','inverse');

            if(in_array($method, $func))
            {
                @list($panel, $for) = $args;

                return $this->create($panel, $for, $method);
            }
            else
            {
                echo '<h1>'.$method.' Function is not Permitted </h1>';
                exit();
            }
        }
    }

    public function render($html=NULL)
    {
        if(isset($this->_navbar))
        {
            $div = new Tag('div',NULL,$this->_attributes);

            $output[] = $div->open();
            $output[] = '<div class="container-fluid">';

            if(isset($this->_brand))
            {
                $output[] = '<div class="navbar-header">';
                $output[] = (new Tag('a',$this->_brand,$this->_brandattr))->render();
                $output[] =  '</div>';
            }

            $output[] = '<div><ul class="nav navbar-nav">';

            foreach ($this->_navbar as $key => $nav) {

                $active = ($key==0) ? 'active' : '';

                if(isset($nav['child']))
                {
                    $child[] = '<ul class="dropdown-menu">';
                    foreach ($nav['child'] as $key => $value) {

                        $a = (new Tag('a',$value['name'],['href'=>$value['link']]))->render();
                        $child[] = (new Tag('li',$a,['class'=>'']))->render();
                    }

                    $child[] = '</ul>';

                    $child_string = implode( PHP_EOL, $child );

                    $caret = '<span class="caret"></span>';
                    $a = (new Tag('a',$nav['name'].$caret,[ 'class'=>"dropdown-toggle",'data-toggle'=>"dropdown",'href'=>"#"]))->render();

                    $a = $a.$child_string;
                    $drop_attr = 'dropdown';
                }
                else
                {
                    $a = (new Tag('a',$nav['name'],['href'=>$nav['link']]))->render();
                    $drop_attr = '';
                }

                $output[] = (new Tag('li',$a,['class'=>[$active,$drop_attr]]))->render();
            }

            $output[] = '</ul></div>';

            $output[] = '</div>';
            $output[] = $div->close();


            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}
