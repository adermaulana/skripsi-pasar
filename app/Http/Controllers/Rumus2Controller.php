<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Terlaris;
use App\User;
use App\Models\Brand;
use App\Models\ProductReview;
use App\Models\Post;
use App\Models\Category;
use App\Models\Product;

class Rumus2Controller extends Controller
{

    public function index()
    {
        if(auth()->check()){
        $authUserId = auth()->user()->id; // ID pengguna yang sedang login
        $recommendedStores = $this->getRecommendedStoresForNewUser($authUserId);
        return view('rumus.rumus2', [
            'recommendedStores' => $recommendedStores
        ]);


        }
        else{
            $brands = Terlaris::orderBy('quantity', 'desc')
            ->limit(3)
            ->get();

            $brandusers = User::latest('id')
                    ->limit(7)
                    ->get();

            $featured=Product::where('status','active')->where('is_featured',1)->orderBy('price','DESC')->limit(2)->get();
            $posts=Post::where('status','active')->orderBy('id','DESC')->limit(3)->get();


            $products=Product::where('status','active')->inRandomOrder()->limit(8)->get();
            $category=Category::where('status','active')->where('is_parent',1)->orderBy('title','ASC')->get();

            // return $category;
            return view('frontend.index')
            ->with('featured',$featured)
            ->with('posts',$posts)
            ->with('brands',$brands)
            ->with('brandusers',$brandusers)
            ->with('product_lists',$products)
            ->with('category_lists',$category);
                    }
    }

    public function getUserInteractions($userId) {
        // Ambil data interaksi pengguna dengan toko dari database
        $userInteractions = DB::table('product_reviews')
            ->where('user_id', $userId)
            ->pluck('average', 'store_id')
            ->toArray();
    
        return $userInteractions;
    }
    

    private function pearson_correlation($user1Interactions, $user2Interactions) {
        // Hitung jumlah interaksi untuk setiap pengguna
        $sum1 = array_sum($user1Interactions);
        $sum2 = array_sum($user2Interactions);
        // Hitung jumlah kuadrat interaksi untuk setiap pengguna
        $sum1Sq = array_sum(array_map(function($x) { return $x * $x; }, $user1Interactions));
        $sum2Sq = array_sum(array_map(function($x) { return $x * $x; }, $user2Interactions));
    
        // Hitung produk titik interaksi
        $pSum = 0;
        foreach ($user1Interactions as $storeId => $interaction) {
            if (isset($user2Interactions[$storeId])) {
                $pSum += $interaction * $user2Interactions[$storeId];
            }
        }
    
        // Hitung nilai Pearson correlation coefficient
        $num = $pSum - ($sum1 * $sum2 / count($user1Interactions));
        $den = sqrt(($sum1Sq - ($sum1 * $sum1 / count($user1Interactions))) * ($sum2Sq - ($sum2 * $sum2 / count($user1Interactions))));
        
        // Hindari pembagian dengan nol
        if ($den == 0) return 0;
    
        return $num / $den;
    }

    public function getRecommendedStoresForNewUser($newUserId) {
        // Hitung similaritas antara user baru dengan user lain
        $userSimilarities = [];
        for ($userId = 2; $userId <= 17; $userId++) {
            if ($userId != $newUserId) {
                $userInteractions = $this->getUserInteractions($userId);
                $newUserInteractions = $this->getUserInteractions($newUserId);
                $similarity = $this->pearson_correlation($newUserInteractions, $userInteractions);
                $userSimilarities[$userId] = $similarity;

            }
        }
        
        // Tentukan user yang paling mirip dengan user baru
        $mostSimilarUser = array_search(max($userSimilarities), $userSimilarities);
        // Ambil toko-toko yang belum dikunjungi oleh user baru dan direkomendasikan oleh user yang paling mirip
        $newUserInteractions = $this->getUserInteractions($newUserId);
        $mostSimilarUserInteractions = $this->getUserInteractions($mostSimilarUser);

        $recommendedStores = [];

        foreach ($mostSimilarUserInteractions as $storeId => $interaction) {
            if (isset($newUserInteractions[$storeId])) {
                $recommendedStores[$storeId] = $interaction;
                
            }
        }
    
        // Urutkan toko-toko berdasarkan interaksi tertinggi dan kembalikan rekomendasi
        arsort($recommendedStores);

        return $recommendedStores;
    }
    
    // Fungsi untuk mengambil toko yang tidak diberi rating oleh pengguna
    private function getStoresNotRatedByUser($userId) {
        // Ambil semua toko dari basis data
        $allStores = range(2, 17); // Asumsi toko memiliki ID dari 1 hingga 15
    
        // Ambil toko yang telah diberi rating oleh pengguna
        $ratedStores = array_keys($this->getUserInteractions($userId));
    
        // Ambil toko yang belum diberi rating oleh pengguna
        $storesNotRated = array_diff($allStores, $ratedStores);
        
        return $storesNotRated; 
    }
    
}
