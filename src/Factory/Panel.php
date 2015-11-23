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

class Panel extends Factory
{
    protected $_panel = NULL;
    protected $_content = NULL;
    protected $_icon = NULL;
    protected $_footer = NULL;
    protected $_attributes
        = array(
            'class' => ['panel'],
        );

    public function content($content)
    {
        $this->_content = $content;

        return $this;
    }

    public function footer($footer)
    {
        $this->_footer = $footer;

        return $this;
    }

    public function icon($icon=NULL)
    {
        $this->_icon = $icon;

        return $this;
    }

    public function build()
    {
        @list($panel, $type) = func_get_args();

        $this->_panel = $panel;

        if(isset($type))
        {
            $this->add_class( 'panel-' . $type );
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

    /**
     * Render
     *
     * @return null|string
     */
    public function render()
    {
        if ( isset( $this->_panel ) )
        {
            $div = new Tag('div', NULL, $this->_attributes);

            $output[] = $div->open();

            $output[] = (new Tag('i',$this->_icon,[]))->render();
            $output[] = (new Tag('div', $this->_panel, ['class'=>'panel panel-heading']))->render();

                if(isset($this->_content))
                {
                    $output[] = (new Tag('div', $this->_content, ['class'=>'panel-body']))->render();
                }

                if(isset($this->_footer))
                {
                    $output[] = (new Tag('div', $this->_footer, ['class'=>'panel-footer']))->render();
                }

            $output[] = $div->close();

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}
