@extends('layouts.app')
@section('title','Edit Checklist')
@section('page-title','Edit Checklist')

@section('content')
<div style="max-width:960px;margin:0 auto;">

<div style="background:var(--navy);border-radius:var(--r);padding:18px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;" class="anim-up">
  <div><div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Vehicle</div><div style="font-family:var(--font-m);font-size:1.5rem;font-weight:700;color:#fff;letter-spacing:2px;">{{ $service->vehicle->registration_no }}</div></div>
  <div style="width:1px;height:40px;background:rgba(255,255,255,.1);"></div>
  <div><div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:2px;">Job Card</div><div style="font-family:var(--font-m);font-size:.9rem;font-weight:700;color:#fff;">{{ $service->job_card_no }}</div></div>
  <div style="width:1px;height:40px;background:rgba(255,255,255,.1);"></div>
  <div><div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:2px;">Type</div><span style="background:{{ $service->service_type==='Full'?'var(--orange)':'var(--sky)' }};color:#fff;padding:3px 10px;border-radius:20px;font-size:.76rem;font-weight:700;">{{ $service->service_type }}</span></div>
  <a href="{{ route('services.show',$service) }}" class="btn btn-outline btn-sm" style="margin-left:auto;background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:#fff;"><i class="fas fa-arrow-left"></i> Back</a>
</div>

<form method="POST" action="{{ route('checklists.update',[$service,$checklist]) }}">
@csrf @method('PUT')

<div style="display:grid;grid-template-columns:1fr 260px;gap:18px;">
  <div>
    @foreach($checklistGroups as $group => $items)
    <div class="card anim-up d1" style="margin-bottom:14px;">
      <div class="card-header"><div class="card-title">{{ $group }}</div></div>
      <div class="card-body" style="padding:14px;">
        @foreach($items as $item)
        @php $done = $checklist->{$item['key']} ?? false; @endphp
        <div class="cl-item {{ $done?'checked':'' }}" id="item-{{ $item['key'] }}" onclick="toggleCheck(this,'{{ $item['key'] }}')">
          <input type="hidden" name="items[{{ $item['key'] }}][done]" value="{{ $done?1:0 }}" id="h-{{ $item['key'] }}">
          <div class="cl-cb"><i class="fas fa-check"></i></div>
          <span class="cl-name">{{ $item['label'] }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endforeach

    <div class="card anim-up">
      <div class="card-header"><div class="card-title"><i class="fas fa-sticky-note" style="color:var(--purple);margin-right:6px;"></i>Notes</div></div>
      <div class="card-body">
        <textarea name="additional_notes" class="form-textarea" style="min-height:90px;">{{ old('additional_notes',$checklist->additional_notes) }}</textarea>
      </div>
    </div>
  </div>

  <div style="position:sticky;top:84px;align-self:start;">
    <div class="card anim-up d2">
      <div class="card-header"><div class="card-title"><i class="fas fa-chart-simple" style="color:var(--orange);margin-right:6px;"></i>Progress</div></div>
      <div class="card-body" style="padding:16px;">
        <div style="text-align:center;margin-bottom:14px;">
          <div style="font-family:var(--font-m);font-size:2rem;font-weight:700;color:var(--navy);" id="editCount">0</div>
          <div style="font-size:.72rem;color:var(--g400);">items checked</div>
        </div>
        <div style="height:8px;background:var(--g100);border-radius:4px;overflow:hidden;margin-bottom:14px;">
          <div id="editBar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--orange),var(--green));border-radius:4px;transition:width .4s;"></div>
        </div>
        <div style="display:flex;gap:8px;margin-bottom:14px;">
          <button type="button" onclick="checkAll(true)"  class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;"><i class="fas fa-check-double"></i> All</button>
          <button type="button" onclick="checkAll(false)" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;color:var(--red);"><i class="fas fa-times"></i> None</button>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Save Changes</button>
      </div>
    </div>
  </div>
</div>
</form>
</div>

@push('scripts')
<script>
const allItems = document.querySelectorAll('.cl-item');
const totalItems = allItems.length;

function toggleCheck(el, key) {
  const isChecked = !el.classList.contains('checked');
  el.classList.toggle('checked', isChecked);
  document.getElementById('h-'+key).value = isChecked ? 1 : 0;
  updateUI();
}

function checkAll(state) {
  allItems.forEach(el => {
    const key = el.id.replace('item-','');
    el.classList.toggle('checked', state);
    document.getElementById('h-'+key).value = state ? 1 : 0;
  });
  updateUI();
}

function updateUI() {
  const count = document.querySelectorAll('.cl-item.checked').length;
  const pct   = totalItems > 0 ? (count / totalItems) * 100 : 0;
  document.getElementById('editCount').textContent = count;
  document.getElementById('editBar').style.width   = pct + '%';
}

updateUI();
</script>
@endpush
@endsection