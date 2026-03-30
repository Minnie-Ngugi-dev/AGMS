@extends('layouts.app')
@section('title','Edit — '.$serviceItem->name)
@section('page-title','Edit Service Item')

@section('content')
<div style="max-width:580px;margin:0 auto;" class="anim-up">

<div class="page-hdr">
  <div>
    <a href="{{ route('admin.service-items.index') }}" class="btn btn-outline btn-sm">
      <i class="fas fa-arrow-left"></i> Back to Service Items
    </a>
  </div>
  <div class="page-hdr-actions">
    <form method="POST" action="{{ route('admin.service-items.destroy', $serviceItem) }}"
      onsubmit="return confirm('Delete {{ addslashes($serviceItem->name) }}?')">
      @csrf @method('DELETE')
      <button class="btn btn-outline btn-sm" style="color:var(--red);border-color:var(--red);">
        <i class="fas fa-trash"></i> Delete
      </button>
    </form>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div class="card-title">
      <i class="fas fa-pen" style="color:var(--orange);margin-right:8px;"></i>
      Edit: {{ $serviceItem->name }}
    </div>
    <span class="badge {{ $serviceItem->is_active ? 'badge-green' : 'badge-gray' }}">
      {{ $serviceItem->is_active ? 'Active' : 'Inactive' }}
    </span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.service-items.update', $serviceItem) }}">
      @csrf @method('PUT')

      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Service Name <span class="req">*</span></label>
        <input type="text" name="name" class="form-input"
          value="{{ old('name', $serviceItem->name) }}"
          required autofocus>
        @error('name')
          <div class="form-error"><i class="fas fa-circle-xmark"></i> {{ $message }}</div>
        @enderror
      </div>

      <div class="form-group" style="margin-bottom:16px;">
        <label class="form-label">Category <span class="req">*</span></label>
        <input type="text" name="category" class="form-input"
          value="{{ old('category', $serviceItem->category) }}"
          list="cat-list" required>
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
            value="{{ old('price', $serviceItem->price) }}"
            min="0" step="0.01" required>
        </div>
        @error('price')
          <div class="form-error"><i class="fas fa-circle-xmark"></i> {{ $message }}</div>
        @enderror
      </div>

      <div class="form-group" style="margin-bottom:20px;">
        <label class="form-label">Description <span style="color:var(--g400);font-weight:400;">(optional)</span></label>
        <textarea name="description" class="form-textarea"
          placeholder="Brief description...">{{ old('description', $serviceItem->description) }}</textarea>
      </div>

      <div class="form-group" style="margin-bottom:22px;">
        <label style="display:flex;align-items:center;gap:10px;cursor:pointer;user-select:none;">
          <input type="checkbox" name="is_active" value="1"
            {{ old('is_active', $serviceItem->is_active) ? 'checked' : '' }}
            style="accent-color:var(--orange);width:17px;height:17px;">
          <div>
            <div class="form-label" style="margin:0;">Active</div>
            <div class="form-hint" style="margin:0;">Inactive items are hidden on the New Service form.</div>
          </div>
        </label>
      </div>

      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:16px;border-top:1px solid var(--g100);">
        <a href="{{ route('admin.service-items.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary">
          <i class="fas fa-save"></i> Update Item
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Last updated --}}
<div style="margin-top:12px;text-align:center;font-size:.72rem;color:var(--g400);">
  Last updated: {{ $serviceItem->updated_at->diffForHumans() }}
  &nbsp;·&nbsp;
  Created: {{ $serviceItem->created_at->format('d M Y') }}
</div>

</div>
@endsection