<?php

namespace App\Controller;
use App\Entity\ApplicationChange;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ApplicationChangeController extends AbstractController
{
    /**
     * This function retrieves changes belonging to an application and delivers it to the view
     * @Route("/application/showChanges/{app_id}", name="application_change")
     */
    public function showChanges($app_id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $app_changes = $em->getRepository('App:ApplicationChange')->findBy(['application_id'=>$app_id],['created' => 'DESC'],10);

        if (!$app_changes) {
            //throw $this->createNotFoundException('Unable to find Application changes.');
            $referer = $request->headers->get('referer');
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'you have no Log for this app!');
            return $this->redirect($this->generateUrl('application'));
        }
        $form =$this->createFormBuilder()
            ->setAction($this->generateUrl('app_applicationchange_save',['app_id'=> $app_id]))
            ->add('Title', TextType::class)
            ->add('Description', TextareaType::class)
            ->add('AuthId', NumberType::class, array( 'attr' => array('type' => 'number')))
            ->add('created', TextType::class,[ 'disabled' => 'true'])
            ->add('updated', TextType::class,[ 'disabled' => 'true'])
            ->add('save', SubmitType::class, array( 'attr' => array('class' => 'btn btn-success')))
            ->getForm();
         return $this->render('application_change/showChanges.html.twig',[
           "app_changes"=> $app_changes,'form' => $form->createView(),'app_id'=>$app_id
       ]);
    }

    /**
     * This Function delivers the Json formed data to be shown in edit form
     * @Route("/application/chang/ajax/{id}")
     */
    public function change(Request $request)
    {
        $id= $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $change = $em->getRepository('App:ApplicationChange')->findOneBy(['id'=>$id]);
        $jsonData = array();
        $idx = 0;
        $temp = array(
            'id' => $change->getId(),
            'title' => $change->getTitle(),
            'description' => $change->getDescription(),
            'auth_id' => $change->getAuthId(),
            'app_id' => $change->getApplicationId(),
            'created' => $change->getCreated(),
            'updated' => $change->getUpdated(),
        );
        $jsonData = $temp;
        return new JsonResponse($jsonData);
    }

    /**
     * This Function saves new changes added by user as a new record and in case of new edit updates the existing record
     * @Route("/application/save/ajax/{app_id}")
     */
    public function save($app_id,Request $request)
    {
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $app_change = $entityManager->getRepository( 'App:ApplicationChange')->find($request->get('form')['save']);
            if (!$app_change) {
                $app_change = new ApplicationChange();
            }
            $app_change->setTitle( $request->get('form')['Title'] );
            $app_change->setDescription( $request->get('form')['Description'] );
            $app_change->setAuthId($request->get('form')['AuthId']);
            $app_change->setApplicationId($app_id);
            $entityManager->persist($app_change);
            $entityManager->flush();
            return $this->redirect($this->generateUrl('application_change',['app_id'=>$app_id]));
    }catch (Doctrine\ORM\EntityNotFoundException $e) {
            var_dump($e);
        }
    }

    /**
     * "This function deletes application from our database"
     * and re-load the page it calls inside the coud be one funktion with ajax return
     *
     * @Route("/applicationChange/del/{id}/{app_id}")
     */
    public function delete($id,$app_id)
    {
        $em = $this->getDoctrine()->getManager();
        //it is better to use cascade functionality of database (if i get time i will back to it to correct it)
        //i did not reach time to read documentation of cascade in Doctrine
        $query = $em->createQuery('DELETE App:ApplicationChange c WHERE c.id = '.$id);
        $query->execute();
        return $this->redirect($this->generateUrl('application_change',['app_id'=>$app_id]));
    }

    /**
     * This Function deletes an element by Ajax
     * @Route("/application/fast_del/ajax/{id}")
     */

    public function fast_delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('DELETE App:ApplicationChange c WHERE c.id = '.$id);
        $query->execute();
        $jsonData = array();
        $idx = 0;
        $jsonData[$idx++] = ['succsee'=>200];
        return new JsonResponse($jsonData);
    }


}
