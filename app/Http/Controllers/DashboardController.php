<?php
namespace App\Http\Controllers;

use App\Models\{Service, Vehicle, Customer, Payment};

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'active_services'  => Service::whereIn('status',['pending','in-progress'])->count(),
            'completed_today'  => Service::where('status','completed')->whereDate('completed_at', today())->count(),
            'total_customers'  => Customer::count(),
            'total_vehicles'   => Vehicle::count(),
            'revenue_today'    => Payment::whereDate('payment_date', today())->sum('amount'),
            'overdue_services' => Vehicle::whereNotNull('next_service_date')->where('next_service_date','<', today())->count(),
        ];
        $activeServices    = Service::with(['vehicle.customer','mechanic'])->whereIn('status',['pending','in-progress'])->latest()->take(10)->get();
        $overdueServices   = Vehicle::whereNotNull('next_service_date')->where('next_service_date','<', today())->take(5)->get();
        $recentPayments    = Payment::with('service.vehicle')->latest()->take(6)->get();
        $upcomingServices  = Vehicle::whereNotNull('next_service_date')->where('next_service_date','>=', today())->orderBy('next_service_date')->take(5)->get();
        $vehiclesByCategory= Vehicle::selectRaw('category, count(*) as count')->groupBy('category')->pluck('count','category');
        $serviceTypeStats  = Service::selectRaw('service_type, count(*) as count')->groupBy('service_type')->get();

        return view('dashboard.index', compact(
            'stats','activeServices','overdueServices','recentPayments',
            'upcomingServices','vehiclesByCategory','serviceTypeStats'
        ));
    }
}