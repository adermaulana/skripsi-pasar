<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductReview;

class RumusController extends Controller
{

    public function index(){

        $auth = auth()->user()->id;

        $productreview = ProductReview::where('user_id',$auth)
                                        ->orderBy('id','desc')
                                        ->get();

        $sumAverageToko = $productreview->sum('average');
        $rataratatoko =  $sumAverageToko / 15;

        dd($rataratatoko);

        $selectedMovie = $movie->id;

        $usersWhoLikedIt = $this->getUsersWhoLiked($selectedMovie);
        $suggestedMovies = $this->getSuggestedMovies($usersWhoLikedIt, $selectedMovie);

        //$suggestedMovies = array_pad([], 10, 0);

        return $suggestedMovies;

        return view('rumus.index',[
            'productreview' => $productreview,
            'rataratatoko' => $rataratatoko
        ]); 
    }
    
    function pearson_correlation($user1, $user2) {
        $sum1 = array_sum($user1);
        $sum2 = array_sum($user2);
        $sum1Sq = array_sum(array_map(function($x) { return $x * $x; }, $user1));
        $sum2Sq = array_sum(array_map(function($x) { return $x * $x; }, $user2));
        $pSum = 0;
        $n = count($user1);
    
        for ($i = 0; $i < $n; $i++) {
            $pSum += $user1[$i] * $user2[$i];
        }
    
        $num = $pSum - ($sum1 * $sum2 / $n);
        $den = sqrt(($sum1Sq - ($sum1 * $sum1 / $n)) * ($sum2Sq - ($sum2 * $sum2 / $n)));
        if ($den == 0) return 0;
    
        return $num / $den;
    }

    private function getUsersWhoLiked($movieID){
        
        $userObjects = DB::table('ratings')
                    ->select('user_id')
                    ->where('movie_id', $movieID)
                    ->where('rating', 5)
                    ->get()
                    ->toArray();
        
        $users = [];

        foreach ($userObjects as $userObject) {
            array_push($users, $userObject->user_id);
        }

        return $users; //array of objects
    }

    private function getSuggestedMovies($users, $id){
        $ratingObjects = DB::table('ratings')
        ->whereIn('user_id', $users)
        ->select('movie_id', 'user_id', 'rating')
        //->whereNotIn('movie_id', [$id])
        ->get();
        //->toArray();

        $movieScores = [];

        foreach ($ratingObjects as $ratingObject) {
            if (isset($movieScores[$ratingObject->movie_id])) {
                $movieScores[$ratingObject->movie_id] += $ratingObject->rating;
            } else {
                $movieScores[$ratingObject->movie_id] = $ratingObject->rating;
            }
        }

        arsort($movieScores);
        unset($movieScores[$id]);
        $movieScores = array_slice($movieScores, 0, 10, true);

        return $movieScores;
    }

    public function getRecommendedStore($users, $storeId) {
        // Initialize an array to store similarity scores between users
        $similarities = [];
        
        // Calculate Pearson correlation coefficient between each user and the target user
        foreach ($users as $user) {
            $userInteractions = $this->getUserInteractions($user); // Get interactions of the current user
            $similarity = $this->pearson_correlation($targetUserInteractions, $userInteractions); // Calculate similarity
            $similarities[$user] = $similarity;
        }
        
        // Sort users based on similarity scores in descending order
        arsort($similarities);
        
        // Retrieve interactions of similar users and aggregate interactions for suggested stores
        $storeScores = [];
        foreach ($similarities as $user => $similarity) {
            $userInteractions = $this->getUserInteractions($user);
            foreach ($userInteractions as $storeID => $interaction) {
                // Exclude the target store and aggregate interactions for other stores
                if ($storeID != $storeId) {
                    if (!isset($storeScores[$storeID])) {
                        $storeScores[$storeID] = 0;
                    }
                    // Weight the interaction by similarity and aggregate
                    $storeScores[$storeID] += $interaction * $similarity;
                }
            }
        }
        
        // Sort suggested stores based on aggregated interactions
        arsort($storeScores);
        
        // Return top 10 suggested stores
        return array_slice($storeScores, 0, 10, true);
    }
    
    
    public function getUserInteractions($userID) {
        // Retrieve interactions of a user from the database and return as an associative array
        $interactions = DB::table('product_reviews')
            ->where('user_id', $userID)
            ->pluck('rating_average', 'store_id')
            ->toArray();
        return $interactions;
    }
    
    
}
