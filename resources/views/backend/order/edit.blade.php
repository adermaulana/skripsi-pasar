@extends('backend.layouts.master')

@section('title','Order Detail')

@section('main-content')
<div class="card">
  <h5 class="card-header">Order Edit</h5>
  <div class="card-body">
    <form action="{{route('order.update',$order->id)}}" method="POST">
      @csrf
      @method('PATCH')
      <div class="form-group">
        @if(isset($response->transaction_status) && $response->transaction_status == 'settlement')
        <small>
          Kode, <b class="badge badge-success">{{ $order->order_number }}</b> telah melakukan pembayaran
        </small><br><hr>
        <label for="status">Status :</label>
        <select name="status" id="" class="form-control">
          <option value="Menunggu" {{($order->status=='Selesai' || $order->status=="Dalam Pengemasan" || $order->status=="Dibatalkan" || $order->status=="Dikirim") ? 'disabled' : ''}}  {{(($order->status=='Menunggu')? 'selected' : '')}}>Menunggu</option>
          <option value="Dalam Pengemasan" {{($order->status=='Selesai'|| $order->status=="Dibatalkan"|| $order->status=="Dikirim")  ? 'disabled' : ''}}  {{(($order->status=='Dalam Pengemasan')? 'selected' : '')}}>Dalam Pengemasan</option>
          <option value="Dikirim" {{($order->status=='Selesai'|| $order->status=="Dibatalkan") ? 'disabled' : ''}}  {{(($order->status=='Dikirim')? 'selected' : '')}}>Dikirim</option>
          <option value="Selesai" {{($order->status=="Dibatalkan") ? 'disabled' : ''}}  {{(($order->status=='Selesai')? 'selected' : '')}}>Selesai</option>
          <option value="Dibatalkan" {{($order->status=='Selesai') ? 'disabled' : ''}}  {{(($order->status=='Dibatalkan')? 'selected' : '')}}>Dibatalkan</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
        @else
        <label for="status">Status :</label>
        <select name="status" id="" class="form-control">
          <option value="Menunggu" {{($order->status=='Selesai' || $order->status=="Dalam Pengemasan" || $order->status=="Dibatalkan" || $order->status=="Dikirim") ? 'disabled' : ''}}  {{(($order->status=='Menunggu')? 'selected' : '')}}>Menunggu</option>
          <option value="Dalam Pengemasan" {{($order->status=='Selesai'|| $order->status=="Dibatalkan"|| $order->status=="Dikirim")  ? 'disabled' : ''}}  {{(($order->status=='Dalam Pengemasan')? 'selected' : '')}}>Dalam Pengemasan</option>
          <option value="Dikirim" {{($order->status=='Selesai'|| $order->status=="Dibatalkan") ? 'disabled' : ''}}  {{(($order->status=='Dikirim')? 'selected' : '')}}>Dikirim</option>
          <option value="Selesai" {{($order->status=="Dibatalkan") ? 'disabled' : ''}}  {{(($order->status=='Selesai')? 'selected' : '')}}>Selesai</option>
          <option value="Dibatalkan" {{($order->status=='Selesai') ? 'disabled' : ''}}  {{(($order->status=='Dibatalkan')? 'selected' : '')}}>Dibatalkan</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
      @endif
    </form>
  </div>
</div>
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
