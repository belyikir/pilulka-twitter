<?php

namespace App\Service;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterApiService
{
    const CONSUMER_KEY = 'Qg9zc469vjeO1DjEVnIzzxLBj';
    const CONSUMER_SECRET = 'OkOzjeMx0ZGktJx0SkMDjy1RkGDciW21Am8MvDHGCVAwiWpAqt';
    const ACCESS_TOKEN = '1425809277462093828-PKjpaLI1IaL71ADpfwDnBE8oygDyJd';
    const ACCESS_TOKEN_SECRET = '7DOdHJB09s2oH5kTOzXFg7qOsABDffYxEenVENy66kcpT';


    public function searchTweets($q): array
    {
        $query = array(
            "q" => strval($q),
            "count" => 100,
            "result_type" => "recent"
        );

        $twitter = new TwitterOAuth(TwitterApiService::CONSUMER_KEY,
            TwitterApiService::CONSUMER_SECRET,
            TwitterApiService::ACCESS_TOKEN,
            TwitterApiService::ACCESS_TOKEN_SECRET);
        $tweets = $twitter->get('search/tweets', $query);


        $parsed_tweets = array();
        foreach ($tweets->statuses as $tweet) {
            $newDate = date("d-m-Y", strtotime($tweet->created_at));
            array_push($parsed_tweets, ['user' => $tweet->user->screen_name, 'text' => $tweet->text, 'date' => $newDate]);
        }

        return $parsed_tweets;
    }

}