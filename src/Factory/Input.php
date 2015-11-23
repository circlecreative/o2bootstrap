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

class Input extends Factory
{
    protected $_input = NULL;
    protected $_attributes
        = array(
            'class' => ['form-control'],
        );

    public function batch(array $input = array())
    {
        foreach ($input as $key => $value) {
            $render_input[] = $this->build($value['label'],$value['name'])->$value['type']($value['value'])->render();
        }

       return implode(PHP_EOL, $render_input);
    }

    public function build()
    {
        @list($input, $for, $value, $type) = func_get_args();

        $this->_input = $input;

        if ( isset( $for ) )
        {
            if(! isset($this->_attributes['id']))
            {
                $this->set_id( 'input-' . $input );
            }

            if(! isset($this->_attributes['for']))
            {
                $this->_attributes[ 'name' ] = $for;
            }
        }

        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }

        return $this;
    }

    public function inline()
    {
        $this->_inline = TRUE;

        return $this;
    }

    public function text($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'text';
        return $this;
    }

    public function textarea($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'textarea';
        return $this;
    }

    public function password($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'password';
        return $this;
    }

    public function datetime($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'datetime';
        return $this;
    }

    public function date($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'date';
        return $this;
    }

    public function local_datetime($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'datetime-local';
        return $this;
    }

    public function month($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'month';
        return $this;
    }

    public function time($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'time';
        return $this;
    }

    public function checkbox($value=NULL,$attr = NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }

        if(isset($attr) && $attr ==='checked')
        {
            $this->_attributes['checked'] = $attr;
        }

        $this->_attributes['type'] = 'checkbox';
        return $this;
    }

    public function radio($value=NULL,$attr = NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }

        if(isset($attr) && $attr ==='checked')
        {
            $this->_attributes['checked'] = $attr;
        }
        $this->_attributes['type'] = 'radio';
        return $this;
    }

    public function select($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'select';
        return $this;
    }

    public function email($value=NULL)
    {
        if(isset($value))
        {
            $this->_attributes[ 'value' ] = $value;
        }
        $this->_attributes['type'] = 'email';
        return $this;
    }

    public function submit($value=NULL)
    {
        if(isset($value))
        {
            $this->add_class('btn-'.$value);
        }
        $this->_attributes['type'] = 'submit';
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
            echo '<h1>'.$method.' Function is not Permitted </h1>';
            exit();
        }
    }

    /**
     * Render
     *
     * @return null|string
     */
    public function render()
    {
        if(!isset($this->_attributes['type']))
        {
            $this->_attributes['type']='';
        }

        if ( isset( $this->_input ) )
        {
            if($this->_attributes['type'] === 'textarea')
            {
                unset($this->_attributes['type']);
                $div = new Tag('div', NULL, ['class'=>'form-group']);

                $output[] = $div->open();

                $output[] = (new Tag('label', $this->_input, ['for'=>$this->_input]))->render();

                $output[] = (new Tag('textarea', NULL, $this->_attributes ))->render();

                $output[] = $div->close();
            }
            elseif($this->_attributes['type'] === 'select')
            {
                $div = new Tag('div', NULL, ['class'=>'form-group']);

                $output[] = $div->open();

                    $output[] = (new Tag('label', $this->_input, ['for'=>$this->_input]))->render();

                    if(is_array($this->_attributes['value']))
                    {
                        $values = $this->_attributes['value'];
                        unset($this->_attributes['value']);

                        $select = new Tag('select', NULL, $this->_attributes );
                        $output[] = $select->open();
                        foreach($values as $value){
                            $output[] = (new Tag('option', $value['name'],array('value'=>$value['id'])))->render();
                        }
                        $output[]= $select->close();
                    }

                $output[] = $div->close();
            }
            elseif($this->_attributes['type'] === 'checkbox' || $this->_attributes['type'] === 'radio')
            {
                if(isset($this->_inline))
                {
                    $attr_group['class'] = $this->_attributes['type'].'-inline';
                }
                else
                {
                    $attr_group['class'] = $this->_attributes['type'];
                }


                $div = new Tag('div', NULL, $attr_group);

                $output[] = $div->open();

                    $label = new Tag('label', $this->_input, ['for'=>$this->_input]);
                    $output[] = $label->open();

                    $output[] = (new Tag('input', NULL, $this->_attributes ))->render();

                    $output[] = $this->_input;

                    $output[] = $label->close();

                $output[] = $div->close();
            }
            elseif($this->_attributes['type'] === 'text')
            {
                $div = new Tag('div', NULL, ['class'=>'form-group']);

                $output[] = $div->open();

                $output[] = (new Tag('label', $this->_input, ['for'=>$this->_input]))->render();

                $output[] = (new Tag('input', NULL, $this->_attributes ))->render();

                $output[] = $div->close();
            }
            elseif($this->_attributes['type'] === 'email')
            {
                $div = new Tag('div', NULL, ['class'=>'form-group']);

                $output[] = $div->open();

                $output[] = (new Tag('label', $this->_input, ['for'=>$this->_input]))->render();

                $output[] = (new Tag('input', NULL, $this->_attributes ))->render();

                $output[] = $div->close();
            }
            elseif($this->_attributes['type'] === 'password')
            {
                $div = new Tag('div', NULL, ['class'=>'form-group']);

                $output[] = $div->open();

                $output[] = (new Tag('label', $this->_input, ['for'=>$this->_input]))->render();

                $output[] = (new Tag('input', NULL, $this->_attributes ))->render();

                $output[] = $div->close();
            }
            elseif($this->_attributes['type'] === 'submit')
            {
                $div = new Tag('div', NULL, ['class'=>'form-group']);

                $output[] = $div->open();

                $output[] = (new Tag('button', $this->_input, $this->_attributes ))->render();

                $output[] = $div->close();
            }
            else
            {
                $output[] = (new Tag('input', NULL, $this->_attributes ))->render();
            }

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}
