<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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


    