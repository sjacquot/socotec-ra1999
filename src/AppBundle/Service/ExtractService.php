<?php
/**
 * Created by PhpStorm.
 * User: pullmedia
 * Date: 15/06/2018
 * Time: 10:09
 */

namespace AppBundle\Service;

use DateTime;

/**
 * \class ExtractService
 * @package AppBundle\Service
 */
class ExtractService
{
    /**
     * ExtractService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $datestr
     * @return null|DateTime
     */
    protected function checkDate($datestr){
        if(strlen($datestr)==0) return null;
        if(strtotime($datestr)!==false){
            $date = new DateTime();
            $date->setTimestamp(strtotime($datestr));
        } else {
            $dateArray = explode(' ', $datestr);
            if(count($dateArray) > 1) {
                for($i=0;$i<count($dateArray);$i++){
                    $datetest = explode('/',$dateArray[$i]);
                    if(count($datetest) == 3){
                        return $date = DateTime::createFromFormat('d/m/Y',$dateArray[$i]);
                    }
                }
                return null;
            } else { // plain direct date from XLS Sheet UK Format
                $date = DateTime::createFromFormat('m/d/Y', $datestr);
            }
        }
        return $date;
    }

    /**
     * Convert Excel Text Array values in Float Array values
     * @param $dataXLS
     * @return array
     */
    protected function ArrayToFloat($dataXLS){
        foreach ($dataXLS as $item)
        {
            $data[] = floatval($item[0]);
        }
        return $data;
    }


}