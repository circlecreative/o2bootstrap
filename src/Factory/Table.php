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

class Table extends Factory
{
    /**
     * Table Caption
     *
     * @access  protected
     * @type    string
     */
    protected $_caption = NULL;

    /**
     * Table Headers
     *
     * @access  protected
     * @type    array
     */
    protected $_headers = array();

    /**
     * Table Rows
     *
     * @access  protected
     * @type    array
     */
    protected $_rows = array();

    /**
     * Table Attributes
     *
     * @access  protected
     * @type    array
     */
    protected $_attributes = array(
        'class' => [ 'table' ]
    );

    /**
     * Table Responsive Flag
     *
     * @access  protected
     * @type    bool
     */
    protected $_responsive = FALSE;

    /**
     * Table Headers
     *
     * @param   array $headers
     *
     * @access  public
     * @return  $this
     */
    public function set_headers( array $headers = array() )
    {
        $this->_headers = $headers;

        return $this;
    }

    /**
     * Set Rows
     *
     * @param   array $rows
     *
     * @access  public
     * @return  $this
     */
    public function set_rows( array $rows = array() )
    {
        $this->_rows = array_merge( $this->_rows, $rows );

        return $this;
    }

    /**
     * build
     */
    public function build(){}

    /**
     * table stripped
     * @return object
     */
    public function striped()
    {
        $this->_attributes[ 'class' ][] = 'table-striped';

        return $this;
    }

    /**
     * table border
     * @return object
     */
    public function bordered()
    {
        $this->_attributes[ 'class' ][] = 'table-bordered';

        return $this;
    }

    /**
     * table hover
     * @return object
     */
    public function hovered()
    {
        $this->_attributes[ 'class' ][] = 'table-hover';

        return $this;
    }

    /**
     * table condensed
     * @return object
     */
    public function condensed()
    {
        $this->_attributes[ 'class' ][] = 'table-condensed';

        return $this;
    }

    /**
     * table responsive
     * @return object
     */
    public function responsive()
    {
        $this->_responsive = TRUE;

        return $this;
    }

    /**
     * Render
     *
     * @access  public
     * @return  string
     */
    public function render(array $attr = array())
    {
        $output = array();

        if( ! empty( $this->_rows ) )
        {
            if( $this->_responsive )
            {
                $div = new Tag('div',NULL,['class'=>['table-responsive']]);
                $output[] = $div->open();
            }

            $this->_attributes['class'] = implode(' ', $this->_attributes['class']);

            $table = new Tag('table',NULL,$this->_attributes);
            $output[] = $table->open();


            if( isset( $this->_caption ) )
            {
                $output[] = (new Tag('caption',$this->_caption,[]))->render();
            }

            $thead = new Tag('thead',[]);
            $output[] = $thead->open();
            foreach( $this->_headers as $key => $header )
            {
                $output[] = (new Tag('th',$header,[]))->render();
            }
            $output[] = $thead->close();


            foreach( $this->_rows as $row )
            {
                $tr = new Tag('tr',NULL,[]);
                $output[] = $tr->open();

                foreach( $this->_headers as $key => $header )
                {
                    $output[] = (new Tag('td',$row[$key],[]))->render();
                }

                $output[] = $tr->close();
            }


            $output[] = $table->close();

            if( $this->_responsive )
            {
                $output[] = $div->close();
            }

            return implode( PHP_EOL, $output );
        }

        return FALSE;
    }
}