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

class Badge extends Factory
{
    protected $_badge = NULL;

    protected $_attributes
        = array(
            'class' => ['badge'],
        );

    public function build()
    {
        @list($badge) = func_get_args();


        $this->_badge = $badge;

        return $this;
    }

    /**
     * Render
     *
     * @return null|string
     */
    public function render()
    {
        if ( isset( $this->_badge ) )
        {
            return (new Tag('span', $this->_badge, $this->_attributes))->render();
        }

        return NULL;
    }
}