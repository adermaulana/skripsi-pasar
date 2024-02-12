<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Notification;
use App\Notifications\StatusNotification;
use App\User;
use App\Models\ProductReview;
class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth = Auth()->user();
        if($auth->role == 'pedagang'){
            $reviews = ProductReview::where('user_id', $auth->id)->get();
        }
        elseif($auth->role == 'nelayan'){
            $reviews = ProductReview::where('user_id', $auth->id)->get();
        }
        else {
            $reviews=ProductReview::getAllReview();
        }
        
        return view('backend.review.index')->with('reviews',$reviews);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'rate_jenisproduk'=>'numeric|min:1',
            'rate_ketersediaanproduk'=>'numeric|min:1',
            'rate_pelayanan'=>'numeric|min:1',
            'rate_kebersihantoko'=>'numeric|min:1',
            'rate_kualitasproduk'=>'numeric|min:1',
            'rate_jumlahpenjualan'=>'numeric|min:1',
        ]);
        $product_info=Product::getProductBySlug($request->slug);
        //  return $product_info;
        // return $request->all();
        $data=$request->all();
        $data['product_id']=$product_info->id;
        $data['user_id']=$request->user()->id;
        $data['store_id']=$product_info->user_id;
        $data['status']='active';

        // dd($data);

        $defaults = [
            'rate_jenisproduk' => 0,
            'rate_ketersediaanproduk' => 0,
            'rate_pelayanan' => 0,
            'rate_kebersihantoko' => 0,
            'rate_kualitasproduk' => 0,
            'rate_jumlahpenjualan' => 0,
        ];

        $data = array_merge($defaults, $data);
        
        $status=ProductReview::updateOrCreate(
            ['user_id' => $data['user_id'], 'product_id' => $data['product_id']],
            $data
        );

        $user=User::where('role','admin')->get();
        $details=[
            'title'=>'New Product Rating!',
            'actionURL'=>route('product-detail',$product_info->slug),
            'fas'=>'fa-star'
        ];
        Notification::send($user,new StatusNotification($details));
        if($status){
            request()->session()->flash('success','Terima Kasih Telah memberikan Rating');
        }
        else{
            request()->session()->flash('error','Something went wrong! Please try again!!');
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $review=ProductReview::find($id);
        // return $review;
        return view('backend.review.edit')->with('review',$review);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $review=ProductReview::find($id);
        if($review){
            // $product_info=Product::getProductBySlug($request->slug);
            //  return $product_info;
            // return $request->all();
            $data=$request->all();
            $status=$review->fill($data)->update();

            // $user=User::where('role','admin')->get();
            // return $user;
            // $details=[
            //     'title'=>'Update Product Rating!',
            //     'actionURL'=>route('product-detail',$product_info->id),
            //     'fas'=>'fa-star'
            // ];
            // Notification::send($user,new StatusNotification($details));
            if($status){
                request()->session()->flash('success','Review Successfully updated');
            }
            else{
                request()->session()->flash('error','Something went wrong! Please try again!!');
            }
        }
        else{
            request()->session()->flash('error','Review not found!!');
        }

        return redirect()->route('review.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $review=ProductReview::find($id);
        $status=$review->delete();
        if($status){
            request()->session()->flash('success','Successfully deleted review');
        }
        else{
            request()->session()->flash('error','Something went wrong! Try again');
        }
        return redirect()->route('review.index');
    }
}
