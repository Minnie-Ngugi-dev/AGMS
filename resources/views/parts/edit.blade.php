@extends('layouts.app')
@section('title','Edit Part')
@section('page-title','Edit Part')

@section('content')
<div style="max-width:640px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-cogs" style="color:var(--green);margin-right:6px;"></i>Edit Part</div>
      <a href="{{ route('services.show',$part->service) }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('parts.update',$part) }}">
        @csrf @method('PUT')
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group">
            <label class="form-label">Part Name <span class="req">*</span></label>
            <input type="text" name="name" class="form-input" value="{{ old('name',$part->name) }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Part Number</label>
            <input type="text" name="part_number" class="form-input" value="{{ old('part_number',$part->part_number) }}" style="font-family:var(--font-m);">
          </div>
          <div class="form-group">
            <label class="form-label">Quantity <span class="req">*</span></label>
            <input type="number" name="quantity" class="form-input" value="{{ old('quantity',$part->quantity) }}" min="1" required oninput="calcTotal()">
          </div>
          <div class="form-group">
            <label class="form-label">Unit Price (KSh) <span class="req">*</span></label>
            <div class="input-group">
              <span class="ig-addon">KSh</span>
              <input type="number" name="unit_price" id="unitPrice" class="form-input" style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;" value="{{ old('unit_price',$part->unit_price) }}" step="0.01" required oninput="calcTotal()">
            </div>
          </div>
        </div>
        <div style="background:var(--green-l);border:1.5px solid #86efac;border-radius:var(--r-sm);padding:12px 16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
          <span style="font-size:.82rem;color:var(--g600);"><i class="fas fa-calculator" style="color:var(--green);margin-right:6px;"></i>Line Total</span>
          <span style="font-family:var(--font-m);font-size:1.1rem;font-weight:700;color:var(--green);" id="lineTotal">KSh {{ number_format($part->total,2) }}</span>
        </div>
        <div class="form-group" style="margin-bottom:16px;">
          <label class="form-label">Notes</label>
          <textarea name="notes" class="form-textarea" style="min-height:70px;">{{ old('notes',$part->notes) }}</textarea>
        </div>
        <div style="display:flex;justify-content:flex-end;gap:10px;">
          <a href="{{ route('services.show',$part->service) }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-green"><i class="fas fa-save"></i> Update Part</button>
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