@extends('layouts.app')
@section('title','Edit Vehicle')
@section('page-title','Edit Vehicle')

@section('content')
<div style="max-width:760px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-car-side" style="color:var(--orange);margin-right:6px;"></i>Edit — <span style="font-family:var(--font-m);color:var(--green);">{{ $vehicle->registration_no }}</span></div>
      <a href="{{ route('vehicles.show',$vehicle) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('vehicles.update',$vehicle) }}">
        @csrf @method('PUT')
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Owner</label>
          <select name="customer_id" class="form-select">
            @foreach($customers as $c)
            <option value="{{ $c->id }}" {{ $vehicle->customer_id==$c->id?'selected':'' }}>{{ $c->name }} — {{ $c->phone }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Registration No <span class="req">*</span></label>
            <input type="text" name="registration_no" class="form-input" value="{{ old('registration_no',$vehicle->registration_no) }}" style="text-transform:uppercase;font-family:var(--font-m);font-weight:700;letter-spacing:1px;" oninput="this.value=this.value.toUpperCase()" required>
          </div>
          <div class="form-group">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
              @foreach(['Car','Van','Mini Truck','Truck','Trailer'] as $cat)
              <option value="{{ $cat }}" {{ $vehicle->category===$cat?'selected':'' }}>{{ $cat }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Make <span class="req">*</span></label>
            <input type="text" name="make" class="form-input" value="{{ old('make',$vehicle->make) }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Model <span class="req">*</span></label>
            <input type="text" name="model" class="form-input" value="{{ old('model',$vehicle->model) }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Year</label>
            <select name="year" class="form-select">
              <option value="">— Year —</option>
              @foreach(range(date('Y'),1990) as $yr)
              <option value="{{ $yr }}" {{ $vehicle->year==$yr?'selected':'' }}>{{ $yr }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Color</label>
            <input type="text" name="color" class="form-input" value="{{ old('color',$vehicle->color) }}">
          </div>
          <div class="form-group">
            <label class="form-label">Chassis No</label>
            <input type="text" name="chassis_no" class="form-input" value="{{ old('chassis_no',$vehicle->chassis_no) }}" style="font-family:var(--font-m);">
          </div>
          <div class="form-group">
            <label class="form-label">Current Mileage</label>
            <div class="input-group">
              <input type="number" name="current_mileage" class="form-input" value="{{ old('current_mileage',$vehicle->current_mileage) }}">
              <span class="ig-addon">km</span>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Next Service Date</label>
            <input type="date" name="next_service_date" class="form-input" value="{{ old('next_service_date',$vehicle->next_service_date?->format('Y-m-d')) }}">
          </div>
          <div class="form-group">
            <label class="form-label">Next Service (km)</label>
            <div class="input-group">
              <input type="number" name="next_service_km" class="form-input" value="{{ old('next_service_km',$vehicle->next_service_km) }}">
              <span class="ig-addon">km</span>
            </div>
          </div>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid var(--g100);">
          <a href="{{ route('vehicles.show',$vehicle) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Vehicle</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection