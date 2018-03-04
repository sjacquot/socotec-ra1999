<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 03/03/2018
 * Time: 18:45
 */

namespace AppBundle\Entity;

/**
 * Class Results
 * Manage all results from XLS 'Résultats Sheet'
 * @package AppBundle\Entity
 */
class Results
{
    /**
     * @var array $data
     * result read from xls sheet
     */
    private $data;

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

}