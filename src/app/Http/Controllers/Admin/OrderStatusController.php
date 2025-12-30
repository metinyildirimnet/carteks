<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderStatuses = OrderStatus::all();
        return view('admin.order_statuses.index', compact('orderStatuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.order_statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:order_statuses,name',
            'color' => 'required|string|max:7',
        ]);

        OrderStatus::create($request->all());

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OrderStatus $orderStatus)
    {
        return view('admin.order_statuses.show', compact('orderStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OrderStatus $orderStatus)
    {
        return view('admin.order_statuses.edit', compact('orderStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OrderStatus $orderStatus)
    {
        $request->validate([
            'name' => 'required|unique:order_statuses,name,' . $orderStatus->id,
            'color' => 'required|string|max:7',
        ]);

        $orderStatus->update($request->all());

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrderStatus $orderStatus)
    {
        $orderStatus->delete();

        return redirect()->route('admin.order-statuses.index')->with('success', 'Order status deleted successfully.');
    }
}