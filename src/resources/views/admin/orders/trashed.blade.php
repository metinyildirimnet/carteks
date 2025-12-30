@extends('admin.layouts.new_app')

@section('title', 'Silinmiş Siparişler')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Silinmiş Siparişler</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-default btn-sm">Sipariş Listesine Geri Dön</a>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Sipariş Kodu</th>
                                <th>Kullanıcı</th>
                                <th>Toplam Tutar</th>
                                <th>Durum</th>
                                <th>Silinme Tarihi</th>
                                <th style="width: 200px">İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->user->name ?? 'Misafir' }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }} ₺</td>
                                    <td><span class="badge" style="background-color: {{ $order->status->color }}; color: #fff;">{{ $order->status->name }}</span></td>
                                    <td>{{ $order->deleted_at->format('d.m.Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('admin.orders.restore', $order->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Geri Yükle</button>
                                        </form>
                                        <form action="{{ route('admin.orders.force-delete', $order->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Bu siparişi kalıcı olarak silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Kalıcı Sil</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Silinmiş sipariş bulunmuyor.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
