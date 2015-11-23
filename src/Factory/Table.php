<?php
/**
 * Created by PhpStorm.
 * User: Steeven
 * Date: 28/10/2015
 * Time: 11:30
 */

namespace O2System\Bootstrap\Drivers;

use O2System\Bootstrap\Interfaces\Driver;

class Table extends Driver
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

    public function striped()
    {
        $this->_attributes[ 'class' ][] = 'table-striped';

        return $this;
    }

    public function bordered()
    {
        $this->_attributes[ 'class' ][] = 'table-bordered';

        return $this;
    }

    public function hovered()
    {
        $this->_attributes[ 'class' ][] = 'table-hover';

        return $this;
    }

    public function condensed()
    {
        $this->_attributes[ 'class' ][] = 'table-condensed';

        return $this;
    }

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
                $output[] = '<div class="table-responsive">';
            }

            $this->_attributes['class'] = implode(' ', $this->_attributes['class']);

            $output[] = '<table' . $this->_stringify_attributes() . '>';

            if( isset( $this->_caption ) )
            {
                $output[] = '<caption>' . $this->_caption . '</caption>';
            }

            foreach( $this->_rows as $row )
            {
                $output[] = '<tr>';
                foreach( $this->_headers as $key => $header )
                {
                    $output[] = '<td>' . $row[ $key ] . '</td>';
                }
                $output[] = '</tr>';
            }

            $output[] = '</table>';

            if( $this->_responsive )
            {
                $output[] = '</div>';
            }
        }

        $output = implode( PHP_EOL, $output );

        print_out( $output );
    }
}