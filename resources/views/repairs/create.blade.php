@extends('layouts.app')
@section('title','Add Repair')
@section('page-title','Add Repair')

@section('content')
<div style="max-width:640px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-tools" style="color:var(--purple);margin-right:6px;"></i>Log Repair / Diagnosis</div>
      <a href="{{ route('services.show',$service) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <div style="background:var(--g50);border:1px solid var(--g200);border-radius:var(--r-sm);padding:12px 16px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
        <div style="width:36px;height:36px;background:var(--navy);border-radius:9px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:.8rem;flex-shrink:0;"><i class="fas fa-car"></i></div>
        <div style="flex:1;">
          <div style="font-family:var(--font-m);font-weight:700;color:var(--navy);font-size:.9rem;">{{ $service->vehicle->registration_no }}</div>
          <div style="font-size:.75rem;color:var(--g500);">{{ $service->job_card_no }}</div>
        </div>
        @if($service->customer_complaint)
        <div style="background:var(--amber-l);border-radius:var(--r-sm);padding:8px 12px;max-width:200px;">
          <div style="font-size:.65rem;font-weight:700;color:var(--amber);text-transform:uppercase;margin-bottom:2px;">Complaint</div>
          <div style="font-size:.78rem;color:var(--g700);font-style:italic;">"{{ Str::limit($service->customer_complaint,60) }}"</div>
        </div>
        @endif
      </div>
      <form method="POST" action="{{ route('services.repairs.store',$service) }}">
        @csrf
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Diagnosis / Fault Found <span class="req">*</span></label>
          <input type="text" name="diagnosis" class="form-input" value="{{ old('diagnosis') }}" placeholder="e.g. Worn brake pads, Engine misfiring on cylinder 3" required list="diagList">
          <datalist id="diagList">
            @foreach(['Worn brake pads','Faulty alternator','Engine misfiring','Oil leak — sump gasket','Coolant leak — radiator hose','Battery fault','Worn shock absorbers','CV joint worn','Timing belt worn','Clutch slipping','Power steering pump leak','AC compressor fault','Suspension ball joint worn','Wheel bearing worn','Exhaust manifold crack'] as $d)
            <option value="{{ $d }}">
            @endforeach
          </datalist>
          @error('diagnosis')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Action Taken</label>
          <textarea name="action_taken" class="form-textarea" placeholder="What was done to fix it…">{{ old('action_taken') }}</textarea>
        </div>
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Labour / Repair Cost (KSh) <span class="req">*</span></label>
            <div class="input-group">
              <span class="ig-addon">KSh</span>
              <input type="number" name="cost" class="form-input" style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;" value="{{ old('cost',0) }}" min="0" step="0.01" required>
            </div>
            @error('cost')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="pending"    >Pending</option>
              <option value="in-progress">In Progress</option>
              <option value="completed" selected>Completed</option>
            </select>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-textarea" style="min-height:70px;" placeholder="Parts needed, follow-up required…">{{ old('notes') }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <a href="{{ route('services.show',$service) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Repair</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection