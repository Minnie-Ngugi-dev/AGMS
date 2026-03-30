@extends('layouts.app')
@section('title','Edit Service')
@section('page-title','Edit Service')

@section('content')
<div style="max-width:680px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-edit" style="color:var(--orange);margin-right:6px;"></i>Edit — <span style="font-family:var(--font-m);">{{ $service->job_card_no }}</span></div>
      <a href="{{ route('services.show',$service) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <div style="background:var(--g50);border:1px solid var(--g200);border-radius:var(--r-sm);padding:12px 16px;margin-bottom:20px;display:flex;gap:16px;flex-wrap:wrap;">
        <div><div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;margin-bottom:2px;">Vehicle</div><div style="font-family:var(--font-m);font-weight:700;color:var(--navy);">{{ $service->vehicle->registration_no }}</div></div>
        <div><div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;margin-bottom:2px;">Customer</div><div style="font-weight:600;color:var(--g800);">{{ $service->vehicle->customer->name }}</div></div>
        <div><div style="font-size:.62rem;color:var(--g400);text-transform:uppercase;margin-bottom:2px;">Type</div><span class="badge {{ $service->service_type==='Full'?'badge-orange':'badge-sky' }}">{{ $service->service_type }}</span></div>
      </div>
      <form method="POST" action="{{ route('services.update',$service) }}">
        @csrf @method('PUT')
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              @foreach(['pending','in-progress','completed','cancelled'] as $st)
              <option value="{{ $st }}" {{ $service->status===$st?'selected':'' }}>{{ ucfirst(str_replace('-',' ',$st)) }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Assigned Mechanic</label>
            <select name="mechanic_id" class="form-select">
              <option value="">— Unassigned —</option>
              @foreach($mechanics as $m)
              <option value="{{ $m->id }}" {{ $service->mechanic_id==$m->id?'selected':'' }}>{{ $m->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">Labour Charge (KSh)</label>
            <div class="input-group">
              <span class="ig-addon">KSh</span>
              <input type="number" name="labour_charge" class="form-input" style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;" value="{{ old('labour_charge',$service->labour_charge) }}" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Discount (KSh)</label>
            <div class="input-group">
              <span class="ig-addon">KSh</span>
              <input type="number" name="discount" class="form-input" style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;" value="{{ old('discount',$service->discount) }}" min="0" step="0.01">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Estimated Completion</label>
            <input type="datetime-local" name="estimated_completion" class="form-input" value="{{ old('estimated_completion',$service->estimated_completion?->format('Y-m-d\TH:i')) }}">
          </div>
          <div class="form-group">
            <label class="form-label">Mileage Out</label>
            <div class="input-group">
              <input type="number" name="mileage_out" class="form-input" value="{{ old('mileage_out',$service->mileage_out) }}" placeholder="{{ $service->mileage_in }}">
              <span class="ig-addon">km</span>
            </div>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:20px;">
          <label class="form-label">Internal Notes</label>
          <textarea name="notes" class="form-textarea">{{ old('notes',$service->notes) }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid var(--g100);">
          <a href="{{ route('services.show',$service) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Service</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection