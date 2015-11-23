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

class Progress extends Factory
{
    protected $_progress = NULL;
    protected $_active = NULL;

    protected $_attributes
        = array(
            'class' => ['progress-bar'],
            'role' => 'progressbar',
            'aria-valuenow'=> 0,
            'aria-valuemin'=> 0,
            'aria-valuemax'=>100,
            'style'=> "width:0%"
        );

    public function striped($active=NULL)
    {
        $this->add_class('progress-bar-striped');

        if(isset($active) && $active==1)
        {
            $this->add_class('active');
        }

        return $this;
    }



    public function build( )
    {
        @list($progress,$type) = func_get_args();

        $this->_attributes['aria-valuenow'] = $progress;
        $this->_attributes['style'] = "width:".$progress."%;" ;


        $this->_progress = $progress;

        if(isset($type))
        {
            $this->add_class( 'progress-bar-' . $type );
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
            $func = array('danger','success','info','warning');

            if(in_array($method, $func))
            {
                @list($progress) = $args;
                return $this->build($progress,$method);
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
        if ( isset( $this->_progress ) )
        {
            $output[] = '<div class="progress">';
            $value = $this->_progress.'% Complete';
            $output[] = (new Tag('div',$value,$this->_attributes))->render();
            $output[] = '</div>';

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}