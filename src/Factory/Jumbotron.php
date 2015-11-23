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
use O2System\Bootstrap\Factory\Link;

class Jumbotron extends Factory
{
    protected $_jumbotron = NULL;
    protected $_header = NULL;
    protected $_description = NULL;
    protected $_link = NULL;
    protected $_attributes
        = array(
            'class' => ['jumbotron']
        );


    public function build()
    {
        @list($header, $description, $link) = func_get_args();

        $this->header($header);
        $this->description($description);

        if(isset($link))
        {
            call_user_func_array(array($this, 'link'), $link);
        }

        return $this;
    }

    public function header($header)
    {
        $this->_header = $header;

        return $this;
    }

    public function description($description)
    {
        $this->_description = $description;

        return $this;
    }


    public function link($link, $href = NULL, $attributes=NULL)
    {
        if(is_null($attributes))
        {
            $attributes = 'build';
        }

        if($link instanceof Link)
        {
            $this->_link = $link->render();
        }
        elseif(is_string($link))
        {
            $newlink = new Link;
            $this->_link = $newlink->{$attributes}($link,$href)->render();
        }

        return $this;
    }


    /**
     * Render
     *
     * @return
     */
    public function render()
    {
        if ( isset( $this->_description ) )
        {

            $jumbotron = new Tag('div', NULL, $this->_attributes);

            $output[] = $jumbotron->open();

            if(isset($this->_header))
            {
                $output[] = (new Tag('h1',$this->_header,array()))->render();
            }

            $output[] = (new Tag('p',$this->_description,array()))->render();

            if(isset($this->_link))
            {
                $link = new Tag('p',NULL,array());

                $output[] = $link->open();

                $output[] = $this->_link;

                $output[] = $link->close();
            }

            $output[] = $jumbotron->close();

            return implode( PHP_EOL, $output );

        }

        return NULL;
    }
}