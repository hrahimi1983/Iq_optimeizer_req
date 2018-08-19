<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\ApplicationChange;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class ApplicationController extends AbstractController
{
    /**
     * "This function retrieves the Application list from the database and delivers to view"
     * @Route("/", name="application")
     */
    public function index()
    {
        $apps = $this->getDoctrine()->getRepository('App:Application')->findBy(array(),array('updated' => 'DESC'));
        $application_add = $this->generateUrl('application_add');
        return $this->render('application/index.html.twig', [
            'apps' => $apps,$application_add
        ]);
    }

    /**
     * "This function adds application to the database plus it adds the first changelog as it is created"
     * @Route("/application/add", name="application_add")
     */
    public function add(Request $request)
    {
        $app = new Application();
        $form =$this->createFormBuilder($app)
            ->add('name', TextType::class,['required'   => true])
            ->add('save', SubmitType::class, array( 'attr' => array('class' => 'btn btn-success')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           try{
               $entityManager = $this->getDoctrine()->getManager();
               $app = $form->getData();
               $entityManager->persist($app);
               $entityManager->flush();
               $app_change = new ApplicationChange();
               $app_change->setTitle( 'Created' );
               $app_change->setDescription( 'Just Created first comment' );
               $app_change->setApplicationId($app->getId());
               $app_change->setAuthId(10);
               $entityManager->persist($app_change);
               $entityManager->flush();
               return $this->redirect('/');
           }catch (Doctrine\ORM\EntityNotFoundException $e) {
               var_dump($e);
           }
        }
        return $this->render('application/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * "This function edits applications property and updates database"
     * by this we are adding also a record to the changelog(ApplicationChange)
     *
     * @Route("/application/edit/{id}", name="app_edit")
     */
    public function edit($id=1,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('App:Application')->find($id);
        if (!$app) {
            throw $this->createNotFoundException('Unable to find Profile Application.');
        }
        $name=$app->getName();
       $form =$this->createFormBuilder($app)
            ->add('name', TextType::class)
            ->add('created', DateTimeType::class,[ 'disabled' => 'true',])
            ->add('Updated', DateTimeType::class,[ 'disabled' => 'true'])
           ->add('save', SubmitType::class, array( 'attr' => array('class' => 'btn btn-success')))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $app_change = new ApplicationChange();
                $app_change->setTitle( 'Edited' );
                $app_change->setDescription( $name ." to ".$form->getData()->getName() );
                $app_change->setApplicationId( $app->getId());
                $app_change->setAuthId(10);
                $app->addApplicationChange($app_change);
                $entityManager = $this->getDoctrine()->getManager();
                $app = $form->getData();
                $app->setUpdated(new \DateTime('now'));
                $entityManager->persist($app);
                $entityManager->flush();
                return $this->redirect('/');

            } catch (Doctrine\ORM\EntityNotFoundException $e) {
                var_dump($e);
            }
        }
        return $this->render('application/edit.html.twig', array(
            'form' => $form->createView(),$app
        ));
    }

    /**
     * "This function deletes application from our database"
     *
     * @Route("/application/del/{id}", name="app_del")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $app = $em->getRepository('App:Application')->find($id);
        $em->remove($app);
        $em->flush();
        //it is better to use cascade functionality of database (if i get time i will back to it to correct it)
        //i did not reach time to read documentation of cascade in Doctrine
        $query = $em->createQuery('DELETE App:ApplicationChange c WHERE c.application_id = '.$id);
        $query->execute();
        return $this->redirect('/');
    }
}
