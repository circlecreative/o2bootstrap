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
use O2System\Bootstrap\Factory\Link;

/**
 *
 * @package Jumbotron
 */
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

    // ------------------------------------------------------------------------

    /**
     * build
     * @return object
     */
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

    // ------------------------------------------------------------------------

    /**
     * header
     * @param string $header
     * @return object
     */
    public function header($header)
    {
        $this->_header = $header;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * description
     * @param string $description
     * @return object
     */
    public function description($description)
    {
        $this->_description = $description;

        return $this;
    }

    // ------------------------------------------------------------------------

    /**
     * link
     * @param string $link
     * @param string $href
     * @param string $attributes
     * @return object
     */
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

    // ------------------------------------------------------------------------

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