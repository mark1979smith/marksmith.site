<?php

namespace Admin\ImagesBundle\Controller;

use Admin\AdminControllerInterface;
use AppBundle\Entity\Image;
use AppBundle\Utils\Api\S3;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller implements AdminControllerInterface
{
    /**
     * @Route("", name="image-manager")
     * @Method({"GET"})
     */
    public function indexAction()
    {
        $s3Api = new S3();
        $response = $s3Api->read();
        $data = [];
        if (isset($response['result'])) {
            $rawResult = unserialize($response['result']);
            $data['images'] = $rawResult['Contents'];
            $data['endpoint_url'] = substr($rawResult['@metadata']['effectiveUri'], 0, strpos($rawResult['@metadata']['effectiveUri'], '?'));
        }
        return $this->render('ImagesBundle:Default:index.html.twig', $data);
    }

    /**
     * @Route("/create", name="image-manager-create")
     * @Method({"GET", "POST"})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        /** @var \AppBundle\Utils\Api\Mysql $api */
        $mysqlApi = $this->get('app.api.mysql');

        $image = new Image();

        $form = $this->createFormBuilder($image)
            ->add('file', FileType::class)
            ->add('save', SubmitType::class, ['label' => 'Upload Image'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $image->getFile();

            $fileName = date('YmdHis') . '--' . md5(uniqid()).'.'.$file->guessExtension();

            $s3Api = new S3();
            $s3Api->setWhat($fileName);
            $s3Api->create(file_get_contents($file->getPathname()));

            $this->addFlash(
                'success',
                'Your image has been uploaded.'
            );

            return $this->redirectToRoute('image-manager');
        }

        return $this->render('ImagesBundle:Default:create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
