@extends('layouts.app')
@section('title','Edit Stock Item')
@section('page-title','Edit Stock Item')

@section('content')
<div style="max-width:640px;margin:0 auto;" class="anim-up">
<div class="page-hdr">
  <div><a href="{{ route('admin.stock.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a></div>
</div>
<div class="card">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-pen" style="color:var(--orange);margin-right:8px;"></i> Edit: {{ $stock->name }}</div>
    <span class="badge badge-navy">Current Qty: {{ $stock->quantity }}</span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admin.stock.update',$stock) }}">
      @csrf @method('PUT')
      <div class="form-grid" style="margin-bottom:16px;">
        <div class="form-group">
          <label class="form-label">Part Name <span class="req">*</span></label>
          <input type="text" name="name" class="form-input" value="{{ old('name',$stock->name) }}" required>
        </div>
        <div class="form-group">
          <label class="form-label">Part Number</label>
          <input type="text" name="part_number" class="form-input" value="{{ $stock->part_number }}" disabled style="opacity:.5;">
          <div class="form-hint">Part number cannot be changed after creation</div>
        </div>
        <div class="form-group">
          <label class="form-label">Category <span class="req">*</span></label>
          <input type="text" name="category" class="form-input" value="{{ old('category',$stock->category) }}" list="cat-list" required>
          <datalist id="cat-list">
            <option>Engine Oil</option><option>Filters</option>
            <option>Brake Parts</option><option>Electrical</option>
            <option>Tyres</option><option>Suspension</option>
          </datalist>
        </div>
        <div class="form-group">
          <label class="form-label">Supplier</label>
          <input type="text" name="supplier" class="form-input" value="{{ old('supplier',$stock->supplier) }}">
        </div>
        <div class="form-group">
          <label class="form-label">Reorder Level <span class="req">*</span></label>
          <input type="number" name="reorder_level" class="form-input" value="{{ old('reorder_level',$stock->reorder_level) }}" min="0" required>
        </div>
        <div class="form-group">
          <label class="form-label">Cost Price (KSh) <span class="req">*</span></label>
          <div class="input-group">
            <span class="ig-addon">KSh</span>
            <input type="number" name="unit_price" class="form-input"
              style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
              value="{{ old('unit_price',$stock->unit_price) }}" min="0" step="0.01" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label">Selling Price (KSh) <span class="req">*</span></label>
          <div class="input-group">
            <span class="ig-addon">KSh</span>
            <input type="number" name="selling_price" class="form-input"
              style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
              value="{{ old('selling_price',$stock->selling_price) }}" min="0" step="0.01" required>
          </div>
        </div>
      </div>
      <div class="form-group" style="margin-bottom:20px;">
        <label class="form-label">Notes</label>
        <textarea name="notes" class="form-textarea">{{ old('notes',$stock->notes) }}</textarea>
      </div>
      <div style="display:flex;gap:10px;justify-content:flex-end;padding-top:14px;border-top:1px solid var(--g100);">
        <a href="{{ route('admin.stock.index') }}" class="btn btn-outline">Cancel</a>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update</button>
      </div>
    </form>
  </div>
</div>
</div>
@endsection