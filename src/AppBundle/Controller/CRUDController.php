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
        //TODO: Finish certificate generation in AppBundle\Service\GenerateCertificate
        $pathDocCertificate = $this->container->get('app.generate_certificate')->generateCertificate($operation);

        $document = $operation->getDocument();
        $document->setPathCertificate($pathDocCertificate);

        $em->persist($document);
        $em->flush();


        return new RedirectResponse('/uploads/media/documents/certificate/'.$pathDocCertificate);
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

        var_dump($operation);die();
        //TODO: repport generation
        //TODO: get the report path
        $pathDocReport = '';

        $document = $operation->getDocument();
        $document->setPathReport($pathDocReport);

        $em->persist($document);
        $em->flush();


        return new RedirectResponse('/uploads/media/documents/report'.$pathDocReport);
    }
}