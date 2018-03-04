<?php
/**
 * Created by PhpStorm.
 * User: paolocastro
 * Date: 04/03/2018
 * Time: 15:39
 */

namespace AppBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CRUDController extends Controller
{
    /**
     * @param $id
     * @return RedirectResponse
     */
    public function CertificateAction($id)
    {
        $operation = $this->admin->getSubject();

        if (!$operation) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }
        $em = $this->getDoctrine()->getManager();

        //TODO: repport generation
        //TODO: get the report path
        $pathDocCertificate = '';

        $document = $operation->getDocument();
        //$document->setCertificate($pathDocReport);

        $em->persist($document);
        $em->flush();


        //return new RedirectResponse('/uploads/doc/3ef86a8dce5c8cd83782136824458fa9.xls');
        return new RedirectResponse('/uploads/doc/'.$pathDocCertificate);
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function ReportAction($id)
    {
        $operation = $this->admin->getSubject();

        if (!$operation) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id: %s', $id));
        }
        $em = $this->getDoctrine()->getManager();

        //TODO: repport generation
        //TODO: get the report path
        $pathDocReport = '';

        $document = $operation->getDocument();
        //$document->setReport($pathDocReport);

        $em->persist($document);
        $em->flush();


        //return new RedirectResponse('/uploads/doc/3ef86a8dce5c8cd83782136824458fa9.xls');
        return new RedirectResponse('/uploads/doc/'.$pathDocReport);
    }
}