<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 26/04/2018
 * Time: 21:50
 */

namespace AppBundle\Twig;

use AppBundle\Entity\Sonometer;
use AppBundle\Entity\NoiseSource;
use AppBundle\Entity\Shockmachine;
use Doctrine\ORM\EntityManagerInterface;

class AppExtension extends \Twig_Extension
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('sonometer', array($this, 'sonometerFilter')),
            new \Twig_SimpleFilter('noiseSource', array($this, 'noiseSourceFilter')),
            new \Twig_SimpleFilter('shockmachine', array($this, 'shockmachineFilter')),
        );
    }

    public function sonometerFilter($id)
    {
        if(is_numeric($id)){
            return $this->em->getRepository(Sonometer::class)->find($id);
        }
        return null;
    }
    public function noiseSourceFilter($id)
    {
        if(is_numeric($id)){
            return $this->em->getRepository(NoiseSource::class)->find($id);
        }
        return null;
    }
    public function shockmachineFilter($id)
    {
        if(is_numeric($id)){
            return $this->em->getRepository(Shockmachine::class)->find($id);
        }
        return null;
    }
}