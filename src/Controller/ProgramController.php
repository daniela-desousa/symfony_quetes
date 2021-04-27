<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProgramType;

class ProgramController extends AbstractController
{
/**
 * @Route("/program", name="program_index")
 */
public function index(): Response
{
    $programs = $this->getDoctrine()->getRepository(Program::class)->findAll();

    return $this->render('program/index.html.twig', [
        'programs' => $programs,
    ]);
}


 /**
     * @Route("program/new", name="new")
     */
    public function new(Request $request): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid()){

            $this->addFlash('notice', 'Your program has been saved !');

            $em = $this->getDoctrine()->getManager();
            $em->persist($program);
            $em->flush();

            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            'form' => $form->createView()
        ]);
    }


/**
     * Getting a program by id
     *
     * @Route("/program/show/{id}", name="program_show")
     * @return Response
     */
    public function show(Program $program)
    {
        $seasons =$program->getSeasons();
        // $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(['program_id' => $id]);
        // $episode = $this->getDoctrine()->getRepository(Episode::class)->findBy(['season_id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException('No program with id : '.$program.' found in program\'s table.');
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
            // 'episode' => $episode,
            'seasons' => $seasons
        ]);
    }

        /**
     * @Route("/program/{programId}/seasons/{seasonId}", name="program_season_show")
     */
    public function showSeason(Program $programId, Season $seasonId)
    {
        return $this->render('Program/season_show.html.twig', ['program' => $programId, 'season' => $seasonId]);
    }

        /**
    * @Route("{programId}/seasons/{seasonId}/episodes/{episodeId}", 
    * name="program_episode_show",
    * requirements={"program"="\d+", "season"="\d+", "episode"="\d+"},
    * methods={"GET"})
    */
    public function showEpisode(Program $programId, Season $seasonId, Episode $episodeId)
    {
        return $this->render('Program/episode_show.html.twig', 
            [
            'program' => $programId, 
            'season' => $seasonId,
            'episode' => $episodeId
            ]);
    }

}


    