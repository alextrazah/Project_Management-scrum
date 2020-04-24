<?php

namespace RhBundle\Controller;

use EntitiesBundle\Entity\Absence;
use EntitiesBundle\Form\AbsenceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use EntitiesBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class AbsenceController extends Controller
{
    public function AfficherAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EntitiesBundle:User')->findAll();

        return $this->render('@Rh\absence\liste_user.html.twig',array(
            'users' => $user,
        ));


    }

    public function test1Action()
    {
        return $this->render('@Rh/absence/liste_user.html.twig');
    }

    public function testAction()
    {

        $em = $this->getDoctrine()->getManager();
        $absence = $em->getRepository('EntitiesBundle:Absence')->findAll();

        return $this->render('@Rh\absence\liste.html.twig',array(
            'absences' => $absence,
        ));


    }


    public function delete1Action(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $absence = $em->getRepository('EntitiesBundle:Absence')->find($id);
        $em->remove($absence);
        $em->flush();
        return $this->redirectToRoute("Afficherab");
    }

    public function createAction( Request $request)
    {

        /* $dompdf = new Dompdf();
        $dompdf->load_html('hello world');
        $dompdf->set_paper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream('document.pdf');*/
        //$em = $this->getDoctrine()->getManager();

        //$user = $em->getRepository('EntitiesBundle:User')->findBy(array('UID'=>'1'));
        //$repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        //$user = $repository->myfindid($id);
        //$user = $em->getRepository('EntitiesBundle:Absence')->find($id);

        $repository=$this->getDoctrine()->getManager()->getRepository('EntitiesBundle:User');
        $user = $repository->finduid();

        $absence= new Absence;
        $form = $this->createForm(AbsenceType::class, $absence);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();

            $em->persist($absence);
            $em->flush();
            /**Email**/
            $message = \Swift_Message::newInstance()
                ->setSubject('Absence')
                ->setFrom('hajer.kotti@esprit.tn')
                ->setTo('hajer.kotti@esprit.tn')
                ->setBody('Absence ajouté ');
            $this->get('mailer')->send($message);
            $this->addFlash('info','Mail sent successfully');

            return $this->redirectToRoute( 'Afficherab');}
        return $this->render('@Rh/absence/ajout.html.twig',array(
            'f' => $form->createView(),
            'users' => $user
        ));


    }

    public function update1Action($id,Request $request)
    {



        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository('EntitiesBundle:Absence')->find($id);
        $form = $this->createForm(AbsenceType::class, $club);
        $form = $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('Afficherab');}

        return $this->render('@Rh/absence/update_ab.html.twig',array('f' => $form->createView()));

    }


    public function AfficherabAction()
    {
        $em = $this->getDoctrine()->getManager();
        $absence = $em->getRepository('EntitiesBundle:Absence')->findAll();

        return $this->render('@Rh\absence\afficher_ab.html.twig',array(
            'absences' => $absence,
        ));


    }

    public function ajouter_absenceAction(Request $request, $id)
    {
        $absence = new Absence();
        $form = $this->createForm(AbsenceType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($absence);
            $em->flush();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->find($id);
            $absence->setIdeUser($user);
            $em->persist($absence);
            $em->flush();
            return $this->redirectToRoute('Afficherab');

        }
        return $this->render('@Rh/absence/ajout.html.twig', array(
            'form' => $form->createView(),

        ));


    }



}
