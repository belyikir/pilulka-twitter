<?php


namespace App\Controller;

use App\Form\TweetType;
use App\Service\TwitterApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TweetsController
 * @package App\Controller
 */
class TweetsController extends AbstractController
{
     private TwitterApiService $twitterApiService;

    /**
     * @param TwitterApiService $twitterApiService
     */
    public function __construct(TwitterApiService $twitterApiService)
    {
        $this->twitterApiService = $twitterApiService;
    }

    /**
     * @Route("/", methods={"GET","POST"},name="get_tweets")
     * @param Request $request
     * @return Response
     */
    public function getTweets(Request $request) : Response
    {
        $result_tweets = array();
        $form = $this->createForm(TweetType::class)
            ->add('submit', SubmitType::class, ['label' => 'Search'])
            ->handleRequest($request);
        if ($form->isSubmitted()) {

            $query=$form->getData();
            $query=$query['query'];

            $result_tweets = $this->twitterApiService->searchTweets($query);
        }
        return $this->render("tweets.html.twig", [
            'tweets' => $result_tweets,
            'form' => $form->createView()
        ]);
    }
}