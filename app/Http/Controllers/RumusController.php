<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RumusController extends Controller
{
    public function recommendItems($user_id)
    {
        // Ambil data peringkat dari pengguna
        $userRatings = DB::table('ratings')->where('user_id', $user_id)->get();

        // Ambil semua data peringkat
        $allRatings = DB::table('ratings')->get();

        // Hitung kesamaan kosinus antar pengguna
        $similarities = [];
        foreach ($allRatings as $otherUser) {
            if ($otherUser->user_id != $user_id) {
                $similarity = $this->calculateCosineSimilarity($userRatings, $otherUser);
                $similarities[$otherUser->user_id] = $similarity;
            }
        }

        // Urutkan pengguna berdasarkan kesamaan terbesar
        arsort($similarities);

        // Ambil item yang belum diberi rating oleh pengguna
        $userRatedItems = $userRatings->pluck('item_id')->toArray();
        $recommendedItems = [];

        foreach ($similarities as $otherUserId => $similarity) {
            $otherUserRatings = DB::table('ratings')->where('user_id', $otherUserId)->get();

            foreach ($otherUserRatings as $item) {
                if (!in_array($item->item_id, $userRatedItems) && !in_array($item->item_id, $recommendedItems)) {
                    $recommendedItems[] = $item->item_id;
                }
            }
        }

        return view('')->json(['recommendations' => $recommendedItems]);
    }

    private function calculateCosineSimilarity($userRatings, $otherUser)
    {
        $dotProduct = 0;
        $magnitudeUser = 0;
        $magnitudeOtherUser = 0;

        foreach ($userRatings as $rating) {
            $otherUserRating = DB::table('ratings')
                ->where('user_id', $otherUser->user_id)
                ->where('item_id', $rating->item_id)
                ->first();

            if ($otherUserRating) {
                $dotProduct += $rating->rating * $otherUserRating->rating;
                $magnitudeUser += pow($rating->rating, 2);
                $magnitudeOtherUser += pow($otherUserRating->rating, 2);
            }
        }

        $magnitude = sqrt($magnitudeUser) * sqrt($magnitudeOtherUser);

        // Handle pembagian dengan nol
        return $magnitude != 0 ? $dotProduct / $magnitude : 0;
    }

    
}



use Illuminate\Database\Eloquent\Model;

class Toko extends Model
{
    protected $fillable = ['nama'];

    public function produkTerlaris()
    {
        return $this->belongsToMany(Produk::class, 'penjualans')
            ->withPivot('jumlah_terjual')
            ->orderBy('pivot_jumlah_terjual', 'desc')
            ->limit(5);
    }
}
