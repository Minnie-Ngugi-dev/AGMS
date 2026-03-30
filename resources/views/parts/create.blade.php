@extends('layouts.app')
@section('title','Add Part')
@section('page-title','Add Part')

@section('content')
<div style="max-width:640px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-cogs" style="color:var(--green);margin-right:6px;"></i>Add Replaced Part</div>
      <a href="{{ route('services.show',$service) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <div style="background:var(--g50);border:1px solid var(--g200);border-radius:var(--r-sm);padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;background:var(--navy);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.8rem;flex-shrink:0;"><i class="fas fa-car"></i></div>
        <div>
          <div style="font-family:var(--font-m);font-weight:700;color:var(--navy);font-size:.9rem;">{{ $service->vehicle->registration_no }}</div>
          <div style="font-size:.75rem;color:var(--g500);">{{ $service->job_card_no }} · {{ $service->vehicle->make }} {{ $service->vehicle->model }}</div>
        </div>
      </div>
      <form method="POST" action="{{ route('services.parts.store',$service) }}">
        @csrf
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Part Name <span class="req">*</span></label>
            <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="e.g. Oil Filter, Brake Pads" required list="partsList">
            <datalist id="partsList">
              @foreach(['Engine Oil Filter','Air Filter','Fuel Filter','Brake Pads (Front)','Brake Pads (Rear)','Spark Plugs','Drive Belt','Coolant','Transmission Fluid','Brake Fluid','Battery','Shock Absorber (Front)','Shock Absorber (Rear)','Water Pump','Thermostat','Radiator','Clutch Disc','CV Joint Boot','Wheel Bearing','Alternator Belt','Wiper Blades'] as $p)
              <option value="{{ $p }}">
              @endforeach
            </datalist>
            @error('name')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Part Number</label>
            <input type="text" name="part_number" class="form-input" value="{{ old('part_number') }}" placeholder="OEM / aftermarket code" style="font-family:var(--font-m);">
          </div>
          <div class="form-group">
            <label class="form-label">Quantity <span class="req">*</span></label>
            <input type="number" name="quantity" class="form-input" value="{{ old('quantity',1) }}" min="1" required oninput="calcTotal()">
            @error('quantity')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Unit Price (KSh) <span class="req">*</span></label>
            <div class="input-group">
              <span class="ig-addon">KSh</span>
              <input type="number" name="unit_price" id="unitPrice" class="form-input" style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;" value="{{ old('unit_price') }}" min="0" step="0.01" placeholder="0.00" required oninput="calcTotal()">
            </div>
            @error('unit_price')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
        </div>
        <div style="background:var(--green-l);border:1.5px solid #86efac;border-radius:var(--r-sm);padding:12px 16px;display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span style="font-size:.82rem;color:var(--g600);"><i class="fas fa-calculator" style="color:var(--green);margin-right:6px;"></i>Line Total</span>
          <span style="font-family:var(--font-m);font-size:1.2rem;font-weight:700;color:var(--green);" id="lineTotal">KSh 0.00</span>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-textarea" style="min-height:70px;" placeholder="Brand, supplier, warranty info…">{{ old('notes') }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <a href="{{ route('services.show',$service) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" name="action" value="save_add" class="btn btn-outline" style="color:var(--sky);border-color:var(--sky);"><i class="fas fa-plus"></i> Save & Add Another</button>
          <button type="submit" name="action" value="save" class="btn btn-green"><i class="fas fa-save"></i> Save Part</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
function calcTotal(){
  const qty  = parseFloat(document.querySelector('input[name="quantity"]').value)||0;
  const price= parseFloat(document.getElementById('unitPrice').value)||0;
  document.getElementById('lineTotal').textContent='KSh '+(qty*price).toLocaleString('en-KE',{minimumFractionDigits:2,maximumFractionDigits:2});
}
</script>
@endpush
@endsection