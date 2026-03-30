@extends('layouts.app')
@section('title','Edit Repair')
@section('page-title','Edit Repair')

@section('content')
<div style="max-width:640px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-tools" style="color:var(--purple);margin-right:6px;"></i>Edit Repair</div>
      <a href="{{ route('services.show',$repair->service) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('repairs.update',$repair) }}">
        @csrf @method('PUT')
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Diagnosis <span class="req">*</span></label>
          <input type="text" name="diagnosis" class="form-input" value="{{ old('diagnosis',$repair->diagnosis) }}" required>
        </div>
        <div class="form-group" style="margin-bottom:14px;">
          <label class="form-label">Action Taken</label>
          <textarea name="action_taken" class="form-textarea">{{ old('action_taken',$repair->action_taken) }}</textarea>
        </div>
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Cost (KSh) <span class="req">*</span></label>
            <div class="input-group">
              <span class="ig-addon">KSh</span>
              <input type="number" name="cost" class="form-input" style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;" value="{{ old('cost',$repair->cost) }}" min="0" step="0.01" required>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              @foreach(['pending','in-progress','completed'] as $st)
              <option value="{{ $st }}" {{ $repair->status===$st?'selected':'' }}>{{ ucfirst($st) }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-textarea" style="min-height:70px;">{{ old('notes',$repair->notes) }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <a href="{{ route('services.show',$repair->service) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Repair</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection