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

class Alert extends Factory
{
    protected $_alert = NULL;
    protected $_dismissible = FALSE;
    protected $_title_string = NULL;
    protected $_title_tag = 'strong';
    protected $_icon = NULL;

    protected $_attributes
        = array(
            'class' => ['alert'],
            'role' => 'alert'
        );

    public function build()
    {
        @list($alert,$type) = func_get_args();

        $this->_alert = $alert;

        if(isset($type))
        {
            $this->add_class( 'alert-' . $type );
        }

        return $this;
    }

    public function dismissible()
    {
        $this->_dismissible = TRUE;
        $this->add_class('alert-dismissible');

        return $this;
    }

    public function title($title, $tag = 'strong')
    {
        $this->_title_string = $title;
        $this->_title_tag = $tag;

        return $this;
    }


    public function icon($icon)
    {
        $this->_icon = $icon;
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
                @list($alert, $for) = $args;

                return $this->build($alert, $method);
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
        if ( isset( $this->_alert ) )
        {
            $div = new Tag('div',NULL,$this->_attributes);

            $output[] = $div->open();

            if($this->_dismissible===TRUE)
            {
                $output[] = (new Tag('button',
                    new Tag('span','&times;',['aria-hidden'=>'true'])
                    , [
                    'type' => 'button',
                    'class' => 'close',
                    'data-dismiss' => 'alert',
                    'aria-label' => 'Close'
                ]))->render();
            }


            if(isset($this->_title_string))
            {
                $title = '';

                if(isset($this->_icon))
                {
                    $title = (new Tag('i',NULL,['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
                }

                $title.= '&nbsp;'.$this->_title_string;

                $output[] = (new Tag($this->_title_tag,$title,array()))->render();
            }
            elseif(isset($this->_icon))
            {
                $output[] = (new Tag('i',NULL,['class'=>$this->_icon,'aria-hidden'=>'true']))->render();
            }

            if(is_array($this->_alert))
            {
                $output[] = '<br />';
                $list = new Tag('ul', NULL,['class' => 'alert-list']);

                $output[] = $list->open();
                foreach($this->_alert as $_alert)
                {
                    $output[] = (new Tag('li', $_alert,array()))->render();
                }

                $output[] = $list->close();
            }
            else
            {
                $output[] = $this->_alert;
            }

            $output[] = $div->close();

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}