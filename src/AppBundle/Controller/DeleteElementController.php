<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 27/04/2018
 * Time: 20:37
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Sonometer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeleteElementController extends Controller
{

    /**
     * @Route("/delete/{type}/{id}")
     * @Method({"DELETE"})
     * @param $id
     * @param $type
     * @return JsonResponse
     */
    public function DeleteElement($id, $type)
    {
        if(is_numeric($id)){
            $type = htmlentities($type);
            $em = $this->getDoctrine()->getEntityManager();

            if($type === 'sonometer'){
                $element = $em->getRepository(Sonometer::class)->find($id);
            }
            if (!$element) {
                return new JsonResponse('couldn\'t remove');
            }

            $em->remove($element);
            $em->flush();
            return new JsonResponse('remove', 200);


        }
    }
}