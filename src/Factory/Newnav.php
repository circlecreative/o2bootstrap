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

class Newnav extends Factory
{
    protected $_attributes = array('class'=>array('navbar'));
    protected $_navbar;

    protected $_nav;

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

    public function nav()
    {

    }

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
        if(isset($this->_attributes['class']))
        {
            unset($this->_attributes['class']);

            $this->_attributes = ['class'=>['nav','navbar-nav']];
        }


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
                @list($panel) = $args;

                return $this->build($panel,$method);
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
        $navbar = new Tag('ul',NULL,$this->_attributes);

        $output[] = $navbar->open();

        foreach ($this->_nav as $name => $attributes) {

            if(isset($attributes['child']))
            {
                $child[] = '<ul class="dropdown-menu">';
                foreach ($nav['child'] as $childname => $childattributes) {

                    $a = (new Tag('a',$childname,$childattributes))->render();
                    $child[] = (new Tag('li',$a,[]))->render();
                }

                $child[] = '</ul>';

                $child_string = implode( PHP_EOL, $child );
                $caret = '<span class="caret"></span>';
                $a = (new Tag('a',$name.$caret,[ 'class'=>"dropdown-toggle",'data-toggle'=>"dropdown",'href'=>"#"]))->render();

                $a = $a.$child_string;
                $drop_attr = 'dropdown';
            }
            else
            {
                $a = (new Tag('a',$name,$attributes))->render();
                $drop_attr = '';
            }

            $output[] = (new Tag('li',$a,['class'=>[$drop_attr]]))->render();
        }

        $output[] = $navbar->close();

        return implode( PHP_EOL, $output );
    }
}
