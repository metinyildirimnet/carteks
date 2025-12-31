<tr data-id="{{ $order->id }}">
    <td><input type="checkbox" class="row-checkbox" value="{{ $order->id }}"></td>
    <td>{{ $order->order_code }}</td>
    <td>
        {{ $order->customer_name }}
        <br>
        <small>{{ $order->customer_city }} - {{ $order->customer_district }}</small>
    </td>
    <td>
        @foreach($order->items as $item)
            {{ $item->product->title ?? 'N/A' }} (x{{ $item->quantity }})<br>
        @endforeach
    </td>
    <td>{{ number_format($order->total_amount, 2) }} ₺</td>
    <td><span class="badge" style="background-color: {{ $order->status->color }}; color: #fff;">{{ $order->status->name }}</span></td>
    <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
    <td>
        <a href="{{ route('admin.orders.show', $order->order_code) }}" class="btn btn-sm btn-info">Detay</a>
    </td>
</tr>
