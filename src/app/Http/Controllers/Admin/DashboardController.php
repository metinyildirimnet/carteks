<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $recentOrders = Order::with('status')->orderBy('created_at', 'desc')->take(5)->get();
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::whereHas('status', function ($query) {
                $query->where('name', 'Pending');
            })->count(),
            'processing' => Order::whereHas('status', function ($query) {
                $query->where('name', 'Processing');
            })->count(),
            'shipped' => Order::whereHas('status', function ($query) {
                $query->where('name', 'Shipped');
            })->count(),
            'delivered' => Order::whereHas('status', function ($query) {
                $query->where('name', 'Delivered');
            })->count(),
            'cancelled' => Order::whereHas('status', function ($query) {
                $query->where('name', 'Cancelled');
            })->count(),
        ];

        return view('dashboard', compact('recentOrders', 'orderStats'));
    }
}
