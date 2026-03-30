<?php
namespace App\Http\Controllers;

use App\Models\{Vehicle, Customer};
use Illuminate\Http\Request;

class VehicleController extends Controller {
    public function index(Request $request) {
        $vehicles = Vehicle::with('customer')
            ->when($request->search, fn($q) => $q->where('registration_no','like',"%{$request->search}%")
                ->orWhere('make','like',"%{$request->search}%")
                ->orWhereHas('customer', fn($c)=>$c->where('name','like',"%{$request->search}%")))
            ->when($request->category, fn($q)=>$q->where('category',$request->category))
            ->latest()->paginate(20);
        return view('vehicles.index', compact('vehicles'));
    }
    public function create() {
        $customers = Customer::orderBy('name')->get();
        return view('vehicles.create', compact('customers'));
    }
    public function store(Request $request) {
        $request->validate([
            'customer_id'     => 'required|exists:customers,id',
            'registration_no' => 'required|string|unique:vehicles,registration_no',
            'make'            => 'required|string',
            'model'           => 'required|string',
            'category'        => 'required|string',
            'current_mileage' => 'required|integer|min:0',
        ]);
        $v = Vehicle::create($request->all());
        return redirect()->route('vehicles.show',$v)->with('success','Vehicle registered.');
    }
    public function show(Vehicle $vehicle) {
        $vehicle->load(['customer','services.payments','services.parts','services.repairs','services.mechanic']);
        return view('vehicles.show', compact('vehicle'));
    }
    public function edit(Vehicle $vehicle) {
        $customers = Customer::orderBy('name')->get();
        return view('vehicles.edit', compact('vehicle','customers'));
    }
    public function update(Request $request, Vehicle $vehicle) {
        $request->validate([
            'registration_no' => 'required|string|unique:vehicles,registration_no,'.$vehicle->id,
            'make'  => 'required|string',
            'model' => 'required|string',
        ]);
        $vehicle->update($request->all());
        return redirect()->route('vehicles.show',$vehicle)->with('success','Vehicle updated.');
    }
    public function destroy(Vehicle $vehicle) {
        $vehicle->delete();
        return redirect()->route('vehicles.index')->with('success','Vehicle deleted.');
    }
}