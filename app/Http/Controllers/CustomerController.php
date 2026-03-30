<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller {
    public function index(Request $request) {
        $customers = Customer::withCount(['vehicles','services'])
            ->selectRaw('customers.*, (SELECT MAX(s.service_date) FROM services s JOIN vehicles v ON s.vehicle_id=v.id WHERE v.customer_id=customers.id) as last_service_date')
            ->when($request->search, fn($q) => $q->where('name','like',"%{$request->search}%")
                ->orWhere('phone','like',"%{$request->search}%")
                ->orWhere('email','like',"%{$request->search}%"))
            ->latest()->paginate(20);
        return view('customers.index', compact('customers'));
    }
    public function create() { return view('customers.create'); }
    public function store(Request $request) {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|unique:customers,phone',
            'email' => 'nullable|email|unique:customers,email',
        ]);
        $c = Customer::create($request->only('name','phone','email','address'));
        return redirect()->route('customers.show',$c)->with('success','Customer added successfully.');
    }
    public function show(Customer $customer) {
        $customer->load('vehicles.services');
        return view('customers.show', compact('customer'));
    }
    public function edit(Customer $customer)  { return view('customers.edit', compact('customer')); }
    public function update(Request $request, Customer $customer) {
        $request->validate([
            'name'  => 'required|string',
            'phone' => 'required|string|unique:customers,phone,'.$customer->id,
            'email' => 'nullable|email|unique:customers,email,'.$customer->id,
        ]);
        $customer->update($request->only('name','phone','email','address'));
        return redirect()->route('customers.show',$customer)->with('success','Customer updated.');
    }

    public function vehiclesJson(Customer $customer)
{
    return response()->json(
        $customer->vehicles()
            ->select('id','registration_no','make','model','year','color','category','current_mileage')
            ->get()
    );
}
    public function destroy(Customer $customer) {
        $customer->delete();
        return redirect()->route('customers.index')->with('success','Customer deleted.');
    }
}