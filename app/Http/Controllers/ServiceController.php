<?php
namespace App\Http\Controllers;

use App\Models\{Service, Vehicle, Customer, Staff, GarageSetting};
use Illuminate\Http\Request;

class ServiceController extends Controller {
    public function index(Request $request) {
        $services = Service::with(['vehicle.customer','mechanic'])
            ->when($request->search, fn($q) => $q->whereHas('vehicle', fn($v)=>$v->where('registration_no','like',"%{$request->search}%"))
                ->orWhereHas('vehicle.customer', fn($c)=>$c->where('name','like',"%{$request->search}%")))
            ->when($request->status,       fn($q)=>$q->where('status',$request->status))
            ->when($request->service_type, fn($q)=>$q->where('service_type',$request->service_type))
            ->when($request->date,         fn($q)=>$q->whereDate('service_date',$request->date))
            ->latest('service_date')->paginate(20);
        return view('services.index', compact('services'));
    }
   public function create()
{
    $serviceItemsByCategory = \App\Models\ServiceItem::active()
        ->orderBy('category')
        ->orderBy('name')
        ->get()
        ->groupBy('category');

    $vehicleMakes = \App\Models\VehicleMake::where('is_active', true)
        ->orderBy('name')
        ->get();

    $mechanics = \App\Models\Staff::mechanics()->active()->get();

    return view('services.create', compact(
        'serviceItemsByCategory',
        'vehicleMakes',
        'mechanics'
    ));
}

public function store(Request $request)
{
    $request->validate([
        'service_date'    => 'required|date',
        'registration_no' => 'required_without:vehicle_id|string',
        'customer_name'   => 'required_without:customer_id|string',
        'customer_phone'  => 'required_without:customer_id|string',
    ]);

    // Get or create customer
    $customer = $request->customer_id
        ? \App\Models\Customer::findOrFail($request->customer_id)
        : \App\Models\Customer::firstOrCreate(
            ['phone' => $request->customer_phone],
            [
                'name'    => $request->customer_name,
                'email'   => $request->customer_email,
                'address' => $request->customer_address,
            ]
        );

    // Get or create vehicle
    $vehicle = $request->vehicle_id
        ? \App\Models\Vehicle::findOrFail($request->vehicle_id)
        : \App\Models\Vehicle::firstOrCreate(
            ['registration_no' => strtoupper($request->registration_no)],
            [
                'customer_id'     => $customer->id,
                'make'            => $request->make,
                'model'           => $request->model,
                'year'            => $request->year,
                'color'           => $request->color,
                'chassis_no'      => $request->chassis_no,
                'category'        => $request->category,
                'current_mileage' => $request->mileage_in,
            ]
        );

    // Create service
    $service = \App\Models\Service::create([
        'vehicle_id'           => $vehicle->id,
        'mechanic_id'          => $request->mechanic_id,
        'job_card_no'          => \App\Models\Service::generateJobCardNo(),
        'service_type'         => $request->service_type ?? 'Regular',
        'status'               => 'pending',
        'service_date'         => $request->service_date,
        'mileage_in'           => $request->mileage_in,
        'driver_name'          => $request->driver_name,
        'driver_phone'         => $request->driver_phone,
        'customer_complaint'   => $request->customer_complaint,
        'labour_charge'        => $request->labour_charge ?? 0,
        'estimated_completion' => $request->estimated_completion,
    ]);

    // Attach selected service items
    if ($request->service_item_ids) {
        foreach ($request->service_item_ids as $itemId) {
            $item = \App\Models\ServiceItem::find($itemId);
            if ($item) {
                $service->orderItems()->create([
                    'service_item_id' => $item->id,
                    'name'            => $item->name,
                    'price'           => $item->price,
                    'quantity'        => 1,
                ]);
            }
        }
    }

    return redirect()->route('services.show', $service)
        ->with('success', "Job card {$service->job_card_no} created successfully.");
}
    public function show(Service $service) {
        $service->load(['vehicle.customer','mechanic','checklist','parts','repairs','payments']);
        $settings = GarageSetting::get();
        return view('services.show', compact('service','settings'));
    }
    public function edit(Service $service) {
        $mechanics = Staff::active()->mechanics()->get();
        return view('services.edit', compact('service','mechanics'));
    }


    public function update(Request $request, Service $service) {

    // Quick status-only update
if ($request->_status_only) {
    $service->update(['status' => $request->status]);
    if ($request->status === 'completed' && !$service->invoice_number) {
        $service->update([
            'completed_at'   => now(),
            'invoice_number' => Service::generateInvoiceNumber(),
            'invoice_date'   => now(),
        ]);
    }
    return back()->with('success', 'Status updated to ' . $request->status);
}
        $service->update($request->only('mechanic_id','status','labour_charge','discount','notes','estimated_completion'));
        return redirect()->route('services.show',$service)->with('success','Service updated.');
    }
    public function destroy(Service $service) {
        $service->delete();
        return redirect()->route('services.index')->with('success','Service deleted.');
    }
    public function markComplete(Service $service) {
        $service->update(['status'=>'completed','completed_at'=>now(),'mileage_out'=>request('mileage_out')]);
        if (!$service->invoice_number) {
            $service->update([
                'invoice_number'    => $service->generateInvoiceNumber(),
                'invoice_date'      => now(),
                'invoice_generated' => true,
            ]);
        }
        return back()->with('success','Service completed. Invoice generated.');
    }
}