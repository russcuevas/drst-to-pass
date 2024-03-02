<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Reports;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    public function StaffDashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->role !== 'staff') {
                return redirect()->route('loginpage');
            } else {
                $products = Product::all();
                // returning the list of product and the view
                return view('staff.staff_dashboard', compact('products'));
            }
        }
        return redirect()->route('loginpage');
    }

    public function getRecentOrders()
    {
        return DB::table('order_details')
            ->join('orders', 'order_details.id', '=', 'orders.order_details_id')
            ->join('users', 'order_details.customer_id', '=', 'users.id')
            ->join('order_statuses', 'orders.id', '=', 'order_statuses.order_id')
            ->join('order_initial_statuses', 'order_statuses.id', '=', 'order_initial_statuses.status_id')
            ->select(
                'orders.reference_number',
                'orders.invoice_number',
                DB::raw('SUM(order_details.total_amount) as total_amount'),
                'orders.payment_method',
                'orders.created_at as order_created_at',
                'order_statuses.status as order_status',
                'order_initial_statuses.initial_status as order_initial_status',
                'users.fullname as fullname'
            )
            ->groupBy('orders.reference_number', 'orders.invoice_number', 'orders.payment_method', 'orders.created_at', 'order_statuses.status', 'order_initial_statuses.initial_status', 'users.fullname')
            ->orderBy('order_created_at', 'desc')
            ->limit(5)
            ->get();
    }
}
