<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\File;
use App\Entity\Talk;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\TalkType;
use App\Repository\TalkRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/talk")
 */
class TalkController extends AbstractController
{
    /**
     * @Route("/", name="talk_index", methods={"GET"})
     */
    public function index(
        TalkRepository $talkRepository,
        UserRepository $userRepository,
        Request        $request): Response
    {
        $talk = new Talk();
        $form = $this->createForm(TalkType::class, $talk);
        $users = $userRepository->findAllExceptUser($this->getUser());
        $talks = $talkRepository->findDiscussion($this->getUser(),   $users[0] );

        return $this->render('talk/index.html.twig', [
            'form' => $form->createView(),
            'receiver' => $users[0],
            'talks' => $talks,
            'users' => $users
        ]);
    }

    /**
     * @Route("/{id}/async", name="talk_index_async", methods={"GET"})
     */
    public function indexAsync(TalkRepository $talkRepository, Request $request, User $receiver = null): Response
    {

        $talks = $talkRepository->findDiscussion($this->getUser(),  $receiver);
        return $this->render('talk/index_async.html.twig', [
            'receiver' => $receiver,
            'talks' => $talks,
        ]);
    }


    /**
     * @Route("/new/{id}", name="talk_new", methods={"GET","POST"})
     */
    public function new(TalkRepository $talkRepository, Request $request, User $receiver): Response
    {
        $talk = new Talk();
        /**@var User $user*/
        $user = $this->getUser();
        $form = $this->createForm(TalkType::class, $talk);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();


        if ($form->isSubmitted() && $form->isValid() && $receiver) {
            $files = $request->files->get("talk")["list_files"];
            $uplods_directory = $this->getParameter('upload_directory');

            foreach ($files as $file) {
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($uplods_directory, $filename);
                $fileEntity = new File();
                $fileEntity->setPath($filename);
                $entityManager->persist($fileEntity);
                $talk->addFile($fileEntity);
            }

            $talk->setSender($user);
            $talk->setReceiver($receiver);
            $talk->setSendDate(new \DateTime());

            $entityManager->persist($talk);
            $entityManager->flush();

            $talks = $talkRepository->findDiscussion($this->getUser(),  $receiver);
            return $this->render('talk/index_async.html.twig', [
                'receiver' => $receiver,
                'talks' => $talks,
            ]);
        }

        return new Response();
    }


    /**
     * @Route("/{id}", name="talk_show", methods={"GET"})
     */
    public function show(Talk $talk): Response
    {
        return $this->render('talk/show.html.twig', [
            'talk' => $talk,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="talk_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Talk $talk): Response
    {
        $form = $this->createForm(TalkType::class, $talk);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('talk_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('talk/edit.html.twig', [
            'talk' => $talk,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="talk_delete", methods={"POST"})
     */
    public function delete(Request $request, Talk $talk): Response
    {
        if ($this->isCsrfTokenValid('delete' . $talk->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($talk);
            $entityManager->flush();
        }

        return $this->redirectToRoute('talk_index', [], Response::HTTP_SEE_OTHER);
    }
}
