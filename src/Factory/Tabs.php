<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 13:24
 */

namespace O2System\Bootstrap\Factory;


use O2System\Bootstrap\Interfaces\Factory;
use O2System\Bootstrap\Factory\Tag;

class Tabs extends Factory
{
    protected $_attributes = array(
        'class' => ['nav'],
        'role' => 'tab'
    );

    protected $_nav = NULL;
    protected $_active = NULL;

    public function stacked($attr=NULL)
    {
        $this->add_class('nav-stacked');

        return $this;
    }

    public function justified($att=NULL)
    {
        $this->add_class('nav-justified');

        return $this;
    }

    public function build( )
    {
        @list($nav,$type) = func_get_args();

        if(is_string($nav))
        {
            $nav = array($nav);
        }

        $this->_nav = $nav;

        if ( isset( $for ) )
        {
            if(! isset($this->_attributes['id']))
            {
                $this->set_id( 'nav-' . $for );
            }
        }

        if(isset($type))
        {
            $this->add_class( 'nav-' . $type);
        }

        return $this;
    }

    public function __call($method, $args = array())
    {
        $method = $method === 'standard' ? 'tabs' : $method;

        if(method_exists($this, $method))
        {
            return call_user_func_array(array($this, $method), $args);
        }
        else
        {
            $func = array('tabs','pills');

            if(in_array($method, $func))
            {
                @list($nav) = $args;

                return $this->build($nav, $method);
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
        if ( isset( $this->_nav ) )
        {
            //--- link tab ---\\
            $ul = new Tag('ul', NULL,$this->_attributes);

            //ul open  tag
            $output[] = $ul->open();

            $i=1;
            foreach ($this->_nav as $key => $nav) {

                $output[] =  ($key==0) ? '<li class="active">' : '<li class="">';

                //a open tag
                $a = new Tag('a',['href'=>'#'.$nav['link'],'role'=>'tab','data-toggle'=>'tab']);
                $output[] = $a->open();

                //for icon
                if(isset($nav['icon']))
                {
                    $output[] = (new Tag('i',['class'=>$nav['icon']]))->render();
                }

                $output[] = $nav['link'];
                //a close tag
                $output[] = $a->close();

                $output[] = '</li>';


                //for content
                $active = ($key==0) ? 'active in' : '';
                $div = new Tag('div',['class'=>['tab-pane','fade',$active],'id'=>$nav['link']]);

                $content[] = $div->open();

                $content[] = $nav['content'].'<h1>'.$i++ .'</h1>';

                $content[] = $div->close();


            }

            $output[] = $ul->close();

            //--- end link tab ---\\

            //--- content tab ---\\

            $divcontent = new Tag('div',['class'=>'tab-content']);

            $output[] = $divcontent->open();

            $output[] = implode( PHP_EOL, $content );

            $output[] = $divcontent->close();
            //--- end content tab ---\\

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}