@extends('layouts.app')
@section('title','Add Service Item')
@section('page-title','Add Service Item')

@section('content')
<div style="max-width:580px;margin:0 auto;" class="anim-up">

<div class="page-hdr">
  <div>
    <a href="{{ route('admin.service-items.index') }}" class="btn btn-outline btn-sm">
      <i class="fas fa-arrow-left"></i> Back to Service Items
    </a>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">
      <i class="fas fa-plus" style="color:var(--orange);margin-right:8px;"></i> New Service Item
    </div>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.service-items.store') }}">
      @csrf

      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Service Name <span class="req">*</span></label>
        <input type="text" name="name" class="form-input"
          value="{{ old('name') }}"
          placeholder="e.g. Engine Oil Change"
          required autofocus>
        @error('name')
          <div class="form-error"><i class="fas fa-circle-xmark"></i> {{ $message }}</div>
        @enderror
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Category <span class="req">*</span></label>
        <input type="text" name="category" class="form-input"
          value="{{ old('category') }}"
          list="cat-list"
          placeholder="e.g. Oils & Fluids"
          required>
        <datalist id="cat-list">
          <option value="Oils & Fluids">
          <option value="Brakes">
          <option value="Tyres">
          <option value="Engine">
          <option value="Electrical">
          <option value="Suspension">
          <option value="Transmission">
          <option value="Cooling System">
          <option value="Body & Exterior">
          <option value="General">
        </datalist>
        <div class="form-hint">Type a new category or choose from the list.</div>
        @error('category')
          <div class="form-error"><i class="fas fa-circle-xmark"></i> {{ $message }}</div>
        @enderror
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Price (KSh) <span class="req">*</span></label>
        <div class="input-group">
          <span class="ig-addon">KSh</span>
          <input type="number" name="price" class="form-input"
            style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
            value="{{ old('price', 0) }}"
            min="0" step="0.01" required>
        </div>
        <div class="form-hint">This price will appear automatically when this service is selected on a job card.</div>
        @error('price')
          <div class="form-error"><i class="fas fa-circle-xmark"></i> {{ $message }}</div>
        @enderror
      </div>

      <div class="form-group" style="margin-bottom:20px;">
        <label class="form-label">Description <span style="color:var(--g400);font-weight:400;">(optional)</span></label>
        <textarea name="description" class="form-textarea"
          placeholder="Brief description of what this service involves...">{{ old('description') }}</textarea>
      </div>

      <div class="form-group" style="margin-bottom:22px;">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;">
          <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', '1') ? 'checked' : '' }}
            style="accent-color:var(--orange);width:17px;height:17px;">
          <div>
            <div class="form-label" style="margin:0;">Active</div>
            <div class="form-hint" style="margin:0;">Active items appear when creating a new service job card.</div>
          </div>
        </label>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid var(--g100);">
        <a href="{{ route('admin.service-items.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Save Service Item
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Tip --}}
<div style="margin-top:14px;padding:12px 16px;background:var(--sky-l);border:1px solid #7dd3fc;border-radius:var(--r-sm);font-size:.78rem;color:var(--sky);">
  <i class="fas fa-circle-info" style="margin-right:6px;"></i>
  <strong>Tip:</strong> After adding items here, they will show up automatically on the New Service form grouped by category. Staff just tick what the car needs and the price is added automatically.
</div>

</div>
@endsection