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
use O2System\Bootstrap\Factory\Input;

class Form extends Factory
{
    protected $_form = NULL;
    protected $_attributes
        = array(
            'class' => ['form'],
            'role' => 'form'
        );

    protected $_style = NULL;

    // ------------------------------------------------------------------------

    /**
     * build
     * @return type
     */
    public function build()
    {
        @list($field, $action, $method,$style) = func_get_args();

        $this->_field = $field;

        $this->_type  = $style;

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
            $this->add_class('form-'.$style);
        }

        return $this;

    }

    // ------------------------------------------------------------------------

    /**
     * __call magoc method
     * @param type $method
     * @param type $args
     * @return type
     */
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
                @list($field,$action,$meth) = $args;

                return $this->build($field,$action,$meth,$method);
            }
            else
            {
                echo '<h1>'.$method.' Function is not Permitted </h1>';
                exit();
            }
        }
    }

    // ------------------------------------------------------------------------

    /**
     * render
     * @return object
     */
    public function render()
    {
        $form = new Tag('form',NULL,$this->_attributes);

        $output[] = $form->open();

        if($this->_type === 'horizontal' )
        {
            foreach ($this->_field as $key => $value)
            {
                $attr = array('class'=>'form-control','name'=>$value['name'],'type'=>$value['type'],'value'=>$value['value']);

                $div = new Tag('div',NULL,['class'=>['form-group']]);
                $input[] = $div->open();
                $input[] = (new Tag('label',$value['name'],['class'=>['control-label','col-sm-2'],'for'=>$value['name']]))->render();
                $divcol = new Tag('div',['class'=>['col-sm-10']]);
                $input[] = $divcol->open();
                $input[] = (new Tag('input', NULL, $attr ))->render();
                $input[] = $divcol->close();
                $input[] = $div->open();
            }

            $wrap = new Tag('div',NULL,['class'=>['form-group']]);
            $output[] = implode(PHP_EOL, $input);
            $output[] = $wrap->open();
            $wrapcol = new Tag('div',['class'=>['col-sm-offset-2','col-sm-10']]);
            $output[] = $wrapcol->open();
            $output[] = (new Tag('button','Submit',['class'=>['btn','btn-default']]))->render();
            $output[] = $wrapcol->close();
            $output[] = $wrap->close();

        }
        else
        {

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

        }

        $output[] = $form->close();

        return implode(PHP_EOL, $output);
    }


}
