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
use O2System\Bootstrap\Factory\Input;

class Form extends Factory
{
    protected $_form = NULL;
    protected $_attributes
        = array(
            'class' => ['form'],
            'role' => 'form'
        );


    public function build()
    {
        @list($field, $action, $method,$style) = func_get_args();

        $this->_field = $field;

        if(isset($method))
        {
            $this->_attributes['method'] = $method;
        }

        if(isset($action))
        {
            $this->_attributes['action'] = $action;
        }

        if(isset($style))
        {
            $this->add_class( 'form-' . $style );
        }

        return $this;

    }

    public function __call($method, $args = array())
    {
        $method = ($method ==='standard') ? 'vertical' : $method;

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
            $func = array('horizontal','inline','vertical');

            if(in_array($method, $func))
            {
                @list($field,$action) = $args;

                return $this->build($field,$action,$func,$method);
            }
            else
            {
                echo '<h1>'.$method.' Function is not Permitted </h1>';
                exit();
            }
        }
    }

    public function render()
    {
        $form = new Tag('form',NULL,$this->_attributes);

        $output[] = $form->open();

        if(isset($this->_field) && is_array($this->_field))
        {
            foreach ($this->_field as $key => $value)
            {
                $input = new Input;
                $render_input[] = $input->build($value['label'],$value['name'])->$value['type']($value['value'])->render();
            }

            $output[] = implode(PHP_EOL, $render_input);
            $output[] = (new Tag('button','Submit',['class'=>['btn','btn-default']]))->render();
        }
        else
        {
            $output[] = $this->_field;
            $output[] = (new Tag('button','Submit',['class'=>['btn','btn-default'],'type'=>'submit']))->render();
        }



        $output[] = $form->close();

        return implode(PHP_EOL, $output);
    }


}