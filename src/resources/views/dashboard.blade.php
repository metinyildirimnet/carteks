@extends('admin.layouts.new_app')

@section('title', 'Dashboard')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $orderStats['total'] }}</h3>
                    <p>Toplam Sipariş</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="small-box-footer">Tümünü Gör <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $orderStats['pending'] }}</h3>
                    <p>Tamamlanmayan Siparişler</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="small-box-footer">Tümünü Gör <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $orderStats['processing'] }}</h3>
                    <p>İşlenen Siparişler</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="small-box-footer">Tümünü Gör <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $orderStats['delivered'] }}</h3>
                    <p>Teslim Edilen Siparişler</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="small-box-footer">Tümünü Gör <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Son Siparişler</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Müşteri</th>
                        <th>Durum</th>
                        <th>Tarih</th>
                        <th style="width: 40px">Tutar</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentOrders as $order)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->id }}</a></td>
                            <td>{{ $order->customer_name }}</td>
                            <td><span class="badge bg-info">{{ $order->status->name }}</span></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_amount, 2) }} ₺</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Henüz sipariş yok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection