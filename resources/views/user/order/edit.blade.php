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
      @if($order->payment_status == 'paid')
      <a class="btn btn-primary disabled">Batalkan</a>
      @else
      <button type="submit" class="btn btn-primary">Batalkan</button>
      @endif
      @if($order->status == 'Dibatalkan')
      <!-- Hilang -->
      @else
            @if($order->payment_method == 'cod')
                  @if($order->payment_status == 'paid')
                  <span class="btn btn-success disabled text-white">Lunas</span>
                  @else
                  <span class="btn btn-success disabled text-white">Bayar Ditempat</span>
                  @endif
            @elseif($order->payment_method == 'transfer')
                @if($order->payment_status == 'paid')
                <a id="pay-button" class="btn btn-success disabled text-white">Lunas</a>
                @else
                <a id="pay-button" class="btn btn-success text-white">Bayar</a>
                @endif
            @endif
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
