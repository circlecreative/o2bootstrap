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
use O2System\Bootstrap\Factory\Tag;

/**
 * Class Bootstrap Alert Builder
 * @package O2Boostrap\Factory
 */
class Button extends Factory
{
    protected $_button = NULL;
    protected $_icon   = NULL;
    protected $_dropdown = NULL;
    protected $_position = NULL;
    protected $_attributes
        = array(
            'class' => ['btn']
        );


    // ------------------------------------------------------------------------

    /**
    *  jika $button data mentah isi harus array()
    *  jika $button hasil dari proses class, isi $button harus string
    */
    public function group($button,$position=NULL)
    {
        if(isset($position) && $position==='right')
        {
            $position = 'pull-right';
        }
        elseif(isset($position) && $position==='left')
        {
            $position = 'pull-left';
        }


        if(isset($button) && is_array($button))
        {
            foreach ($button as $name => $attributes)
            {
                unset($this->_attributes);
                $this->_attributes = array('class'=>['btn']);
                $this->build($name,$attributes['type'],$attributes['class']);
                $output[] = $this->render();
            }

            $div = new Tag('div',['class'=>['btn-group',$position],'role'=>'group']);
            $render[] = $div->open();
            $render[] = implode(PHP_EOL, $output);
            $render[] = $div->close();

            return implode(PHP_EOL, $render);
        }
        else
        {
            $div = new Tag('div',['class'=>['btn-group',$position],'role'=>'group']);
            $render[] = $div->open()
            $render[] = $button;
            $render[] = $div->close();

            return implode(PHP_EOL, $render);
        }

        return FALSE;
    }


     /**
      * toolbar
      * @param string | array $group
      * @param string $position
      * @return object
      */
    public function toolbar($group,$position=NULL,$style=NULL)
    {
        if(isset($position) && $position==='right')
        {
            $position = 'pull-right';
        }
        elseif(isset($position) && $position==='left')
        {
            $position = 'pull-left';
        }

        if(isset($group) && is_array($group))
        {
            //build button
            foreach($group as $key => $value)
            {
                foreach ($value as $key => $button) {
                    $render_group[] = $this->group($button);
                }

            }

            $render[] = '<div class="btn-toolbar '.$position.'" role="toolbar">';
            $render[] = implode(PHP_EOL, $render_group);
            $render[] = '</div>';

            return implode(PHP_EOL, $render);
        }
        else
        {
            $render[] = '<div class="btn-toolbar '.$position.'" role="group">';
            $render[] = $group;
            $render[] = '</div>';

            return implode(PHP_EOL, $render);
        }

        return NULL;

    }

    // ------------------------------------------------------------------------

    /**
     * Dropdown
     * @param array $dropdown
     * @param string $pull position
     * @return type
     */
    public function dropdown(array $dropdown=array(),$pull=NULL)
    {
        $this->_dropdown = $dropdown;

        $this->add_class('dropdown-toggle');
        $this->_attributes['data-toggle'] = "dropdown";

        if(!is_null($pull) && $pull === 'right')
        {
             $this->_position = 'dropdown-menu-right';
        }
        elseif(!is_null($pull) && $pull === 'left')
        {
            $this->_position = 'dropdown-menu-left';
        }



        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Dropup
     * @param array $dropdown
     * @param string $pull position
     * @return object
     */
    public function dropup(array $dropdown=array(),$pull=NULL)
    {
        $this->_dropup = $dropdown;
        $this->add_class('dropdown-toggle');
        $this->_attributes['data-toggle'] = "dropdown";

        if(!is_null($pull) && $pull === 'left')
        {
             $this->_position = 'dropdown-menu-left';
        }
        elseif(!is_null($pull) && $pull === 'left')
        {
            $this->_position = 'dropdown-menu-left';
        }

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * Icon
     * @param string $icon
     * @return object
     */
    public function icon($icon=NULL)
    {
        $this->_icon = $icon;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * tiny
     * @return object
     */
    public function tiny()
    {
        $this->add_class('btn-xs');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * small
     * @return object
     */
    public function small()
    {
        $this->add_class('btn-sm');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * medium
     * @return object
     */
    public function medium()
    {
        $this->add_class('btn-md');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * large
     * @return object
     */
    public function large()
    {
        $this->add_class('btn-lg');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * block
     * @return object
     */
    public function block()
    {
        $this->add_class('btn-block');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * active
     * @return object
     */
    public function active()
    {
        $this->add_class('active');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * disable
     * @return object
     */
    public function disable()
    {
        $this->add_class('disabled');

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * left
     * @return object
     */
    public function left()
    {
        $this->add_class('pull-left');
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * right
     * @return object
     */
    public function right()
    {
        $this->add_class('pull-right');
        return $this;
    }

    public function submit()
    {
        $this->_attributes['type'] = 'submit';
        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object
     */
    public function build( )
    {
        @list($button, $type, $style) = func_get_args();

        $this->_button = $button;

        if(! isset($this->_attributes['type']))
        {
            $this->_attributes[ 'type' ] = 'button';
        }

        if(isset($style))
        {
            $this->add_class( 'btn-' . $style );
        }
        else
        {
            $this->add_class( 'btn-' . $type );
        }

        return $this;
    }

    /**
     * __call magid method
     * @param string $method
     * @param array $args
     * @return object
     */
    public function __call($method, $args = array())
    {
        $method = $method === 'error' ? 'danger' : $method;

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
            $func = array('danger','default','primary','success','info','warning','link','group','toolbar');

            if(in_array($method, $func))
            {
                @list($button, $type) = $args;

                return $this->build($button, $type, $method);
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
        if ( isset( $this->_button ) )
        {
            $button = new Tag('button', NULL, $this->_attributes);

            if(isset($this->_dropdown))
            {
                $output[] = '<div class="dropdown">';
            }
            elseif(isset($this->_dropup))
            {
                $output[] = '<div class="dropup">';
            }

            $output[] = $button->open();

            if(isset($this->_icon))
            {
                $output[] = (new Tag('span',NULL,['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
            }

            $output[] = $this->_button;

            $output[] = (isset($this->_dropdown) || isset($this->_dropup)) ? '<span class="caret"></span>' : '';

            $output[] = $button->close();

            if(isset($this->_dropdown))
            {
                $position = (isset($this->_position)) ? $this->position :'';
                $drop[] = '<ul class="dropdown-menu '.$position.'">';
                foreach ($this->_dropdown as $key => $value) {

                    if(isset($value['header']) && $value['header']===TRUE)
                    {
                        $drop[] = (new Tag('li',$value['name'],array('class'=>'dropdown-header')))->render();

                        if(isset($value['divider']) && $value['divider']===TRUE)
                        {
                            $drop[] = (new Tag('li',NULL,array('role'=>'separator','class'=>'divider')))->render();
                        }
                    }
                    else
                    {
                        $attr = (isset($value['disable']) && $value['disable']===TRUE) ? ['class'=>'disabled'] : array();
                        $a = (new Tag('a',$value['name'],['href'=>$value['link']]))->render();
                        $drop[] = (new Tag('li',$a,$attr))->render();

                        if(isset($value['divider']) && $value['divider']===TRUE)
                        {
                            $drop[] = (new Tag('li',NULL,array('role'=>'separator','class'=>'divider')))->render();
                        }
                    }

                }


                $drop[] = '</ul>';

                $output[] = implode( PHP_EOL, $drop );
                $output[] = '</div>';
            }


            if(isset($this->_dropup))
            {
                $position = (isset($this->_position)) ? $this->position : '';
                $drop[] = '<ul class="dropdown-menu '.$position.'">';
                foreach ($this->_dropup as $key => $value)
                {

                    if(isset($value['header']) && $value['header']===TRUE)
                    {
                        $drop[] = (new Tag('li',$value['name'],array('class'=>'dropdown-header')))->render();

                        if(isset($value['divider']) && $value['divider']===TRUE)
                        {
                            $drop[] = (new Tag('li',NULL,array('role'=>'separator','class'=>'divider')))->render();
                        }
                    }
                    else
                    {
                        $attr = (isset($value['disable']) && $value['disable']===TRUE) ? ['class'=>'disabled'] : array();
                        $a = (new Tag('a',$value['name'],['href'=>$value['link']]))->render();
                        $drop[] = (new Tag('li',$a,$attr))->render();

                        if(isset($value['divider']) && $value['divider']===TRUE)
                        {
                            $drop[] = (new Tag('li',NULL,array('role'=>'separator','class'=>'divider')))->render();
                        }
                    }
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
