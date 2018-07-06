<?php
/**
 * Created by PhpStorm.
 * User: mhandlon
 * Date: 7/3/18
 * Time: 7:10 PM
 */
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;

// php bin/console server:run
// php -S 127.0.0.1:8000 -t public
// http://localhost:8000/

class DefaultController extends Controller
{
    public function index(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->setAction($this->generateUrl('app_form_index'))
            ->add('username', TextType::class)
            ->add('password', PasswordType::class)
            ->add('fullname', TextType::class)
            ->add('submit', SubmitType::class, array('label' => 'Submit'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData();
            //dump($user);

            $user_repo = $this->getDoctrine()
                ->getRepository('App:User');
            //dd($user_repo);
            $search_for_username = $user_repo->findBy(['username' => $user->getUsername()]);

            if (empty($search_for_username)){
                //dd($search_for_username);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->render('default/success.html.twig', array(
                    'form' => $form->createView(),
                ));
            } else {
                return $this->render('default/error.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        }

        return $this->render('default/default.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}