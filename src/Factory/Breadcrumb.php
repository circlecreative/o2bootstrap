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

class Breadcrumb extends Factory
{
    protected $_breadcrumb = NULL;

    protected $_attributes
        = array(
            'class' => ['breadcrumb'],
        );

    public function build( )
    {

        @list($breadcrumb,$type) =func_get_args();

        $this->_breadcrumb = $breadcrumb;

        if(isset($type))
        {
            $this->add_class( 'breadcrumb-' . $type );
        }

        return $this;
    }


    /**
     * Render
     *
     * @return null|string
     */
    public function render()
    {
        if ( isset( $this->_breadcrumb ) )
        {
            $ol = new Tag('ol',NULL,$this->_attributes);
            $output[] = $ol->open();
            $c = count($this->_breadcrumb);
            foreach ($this->_breadcrumb as $key => $value) {

                if($key==$c-1)
                {
                    $output[] = (new Tag('li',$value['name'],['class'=>'active']))->render();
                }
                else
                {
                    $a = (new Tag('a',$value['name'],['href'=>$value['link']]))->render();
                    $output[] = (new Tag('li',$a,['class'=>'']))->render();
                }
            }

            $output[] = $ol->close();

            return implode( PHP_EOL, $output );
        }

        return NULL;
    }
}