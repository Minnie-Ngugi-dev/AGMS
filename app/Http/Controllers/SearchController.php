<?php
namespace App\Http\Controllers;

use App\Models\{Vehicle, Customer, Service};
use Illuminate\Http\Request;

class SearchController extends Controller {
    public function global(Request $request) {
        $q = $request->q;
        $results = [];
        Vehicle::where('registration_no','like',"%$q%")->with('customer')->take(5)->get()->each(function($v) use (&$results) {
            $results[] = ['title'=>$v->registration_no,'subtitle'=>$v->make.' '.$v->model.' · '.$v->customer->name,'icon'=>'car','url'=>route('vehicles.show',$v)];
        });
        Customer::where('name','like',"%$q%")->orWhere('phone','like',"%$q%")->take(5)->get()->each(function($c) use (&$results) {
            $results[] = ['title'=>$c->name,'subtitle'=>$c->phone,'icon'=>'user','url'=>route('customers.show',$c)];
        });
        Service::where('job_card_no','like',"%$q%")->with('vehicle')->take(3)->get()->each(function($s) use (&$results) {
            $results[] = ['title'=>$s->job_card_no,'subtitle'=>$s->vehicle->registration_no.' · '.$s->service_type,'icon'=>'wrench','url'=>route('services.show',$s)];
        });
        return response()->json($results);
    }


    public function customerSearch(Request $request)
{
    $q = $request->get('q','');

    $customers = \App\Models\Customer::where('name','like',"%{$q}%")
        ->orWhere('phone','like',"%{$q}%")
        ->withCount('vehicles')
        ->limit(8)
        ->get()
        ->map(fn($c) => [
            'id'             => $c->id,
            'name'           => $c->name,
            'phone'          => $c->phone,
            'email'          => $c->email,
            'address'        => $c->address,
            'vehicles_count' => $c->vehicles_count,
        ]);

    return response()->json($customers);
}
    public function vehicle(Request $request) {
        return Vehicle::where('registration_no','like',"%{$request->q}%")
            ->orWhereHas('customer', fn($c)=>$c->where('name','like',"%{$request->q}%")->orWhere('phone','like',"%{$request->q}%"))
            ->with(['customer','services'=>fn($s)=>$s->latest()->take(1)])
            ->take(8)->get()
            ->map(fn($v) => [
                'id'=>$v->id,'customer_id'=>$v->customer_id,
                'registration_no'=>$v->registration_no,
                'make'=>$v->make,'model'=>$v->model,
                'customer_name'=>$v->customer->name,
                'customer_phone'=>$v->customer->phone,
                'last_service'=>$v->services->first()?->service_date?->format('d M Y') ?? 'Never',
            ]);

            
    }
}