<?php
namespace App\Http\Controllers;

use App\Models\{Service, Staff};
use Illuminate\Http\Request;

class ReportController extends Controller {
    public function byDate(Request $request) {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to   ?? now()->format('Y-m-d');
        $services = Service::with(['vehicle.customer','payments','parts','repairs'])
            ->whereBetween('service_date',[$from,$to])
            ->when($request->service_type, fn($q)=>$q->where('service_type',$request->service_type))
            ->latest('service_date')->paginate(25)->appends($request->all());
        $all = Service::with(['payments','parts','repairs'])->whereBetween('service_date',[$from,$to])->get();
        $summary = [
            'total_services'    => $all->count(),
            'completed'         => $all->where('status','completed')->count(),
            'total_revenue'     => $all->sum('amount_paid'),
            'avg_service_value' => $all->count() > 0 ? round($all->avg('total_cost')) : 0,
        ];
        return view('reports.date', compact('services','summary'));
    }
    public function byVehicleType(Request $request) {
        $stats = Service::with('vehicle')
            ->when($request->from, fn($q)=>$q->where('service_date','>=',$request->from))
            ->when($request->to,   fn($q)=>$q->where('service_date','<=',$request->to))
            ->get()->groupBy('vehicle.category')
            ->map(fn($g,$cat)=>['category'=>$cat,'count'=>$g->count(),'revenue'=>$g->sum('total_cost')])->values();
        return view('reports.vehicle-type', compact('stats'));
    }
    public function byServiceType(Request $request) {
        $from = $request->from ?? now()->startOfMonth()->format('Y-m-d');
        $to   = $request->to   ?? now()->format('Y-m-d');
        foreach (['Regular','Full'] as $type) {
            $col = Service::where('service_type',$type)->whereBetween('service_date',[$from,$to])->with(['parts','repairs','payments'])->get();
            $stats[$type] = ['count'=>$col->count(),'revenue'=>$col->sum('total_cost'),'paid'=>$col->sum('amount_paid')];
        }
        $services = Service::with(['vehicle.customer','payments'])->whereBetween('service_date',[$from,$to])
            ->when($request->service_type, fn($q)=>$q->where('service_type',$request->service_type))
            ->latest('service_date')->paginate(25)->appends($request->all());
        return view('reports.service-type', compact('stats','services','from','to'));
    }
    public function custom(Request $request) {
        $services = collect(); $summary = [];
        $mechanics = Staff::active()->mechanics()->get();
        if ($request->filled('from')) {
            $q = Service::with(['vehicle.customer','parts','repairs','payments'])
                ->when($request->from,             fn($q)=>$q->where('service_date','>=',$request->from))
                ->when($request->to,               fn($q)=>$q->where('service_date','<=',$request->to))
                ->when($request->service_type,     fn($q)=>$q->where('service_type',$request->service_type))
                ->when($request->status,           fn($q)=>$q->where('status',$request->status))
                ->when($request->mechanic_id,      fn($q)=>$q->where('mechanic_id',$request->mechanic_id))
                ->when($request->vehicle_category, fn($q)=>$q->whereHas('vehicle',fn($v)=>$v->where('category',$request->vehicle_category)));
            $services = $q->latest('service_date')->paginate(30)->appends($request->all());
            $all = $q->get();
            $summary = ['total'=>$all->count(),'completed'=>$all->where('status','completed')->count(),'revenue'=>$all->sum('total_cost'),'outstanding'=>$all->sum('balance')];
        }
        return view('reports.custom', compact('services','summary','mechanics'));
    }
}