@extends('user.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <h5 class="card-header">Order Edit</h5>
  <div class="card-body">
    <form action="{{route('user.order.update',$order->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="form-group">
      <label for="">Kode Order :</label>
      <label for="status"><b>{{ $order->order_number }}</b></label>
      </div>
      <div class="form-group">
      <label for="">Total :</label>
      <label for="status"><b>Rp.{{ $order->total_amount }}</b></label>
      </div>
      <div class="form-group">
        <input type="hidden" name="status" value="Dibatalkan">
      </div>
      <button type="submit" class="btn btn-primary">Batalkan</button>
      @if($order->status == 'Dibatalkan')
      <!-- Hilang -->
      @else
      <a id="pay-button" class="btn btn-success text-white">Bayar</a>
      @endif
    </form>
  </div>
</div>

      <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
      <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
          // SnapToken acquired from previous step
          snap.pay('<?=$order->snap_token ?>', {
            // Optional
            onSuccess: function(result){
              /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            // Optional
            onPending: function(result){
              /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            },
            // Optional
            onError: function(result){
              /* You may add your own js here, this is just example */ document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
            }
          });
        };
      </script>

@endsection

@push('styles')
<style>
    .order-info,.shipping-info{
        background:#ECECEC;
        padding:20px;
    }
    .order-info h4,.shipping-info h4{
        text-decoration: underline;
    }

</style>
@endpush
