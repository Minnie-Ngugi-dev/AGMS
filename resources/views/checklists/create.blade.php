@extends('layouts.app')
@section('title','Service Checklist')
@section('page-title','Fill Checklist')

@section('content')
<div style="max-width:960px;margin:0 auto;">

{{-- Car banner --}}
<div style="background:var(--navy);border-radius:var(--r);padding:18px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;flex-wrap:wrap;" class="anim-up">
  <div>
    <div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;margin-bottom:3px;">Vehicle</div>
    <div style="font-family:var(--font-m);font-size:1.5rem;font-weight:700;color:#fff;letter-spacing:2px;">{{ $service->vehicle->registration_no }}</div>
  </div>
  <div style="width:1px;height:40px;background:rgba(255,255,255,.1);"></div>
  <div><div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:2px;">Job Card</div><div style="font-family:var(--font-m);font-size:.9rem;font-weight:700;color:#fff;">{{ $service->job_card_no }}</div></div>
  <div style="width:1px;height:40px;background:rgba(255,255,255,.1);"></div>
  <div><div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:2px;">Make / Model</div><div style="font-size:.85rem;color:rgba(255,255,255,.7);">{{ $service->vehicle->make }} {{ $service->vehicle->model }}</div></div>
  <div style="width:1px;height:40px;background:rgba(255,255,255,.1);"></div>
  <div><div style="font-size:.62rem;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:2px;">Service Type</div><span style="background:{{ $service->service_type==='Full'?'var(--orange)':'var(--sky)' }};color:#fff;padding:3px 12px;border-radius:20px;font-size:.76rem;font-weight:700;">{{ $service->service_type }}</span></div>
  <div style="margin-left:auto;display:flex;gap:8px;">
    <button type="button" onclick="checkAll(true)"  class="btn btn-green btn-sm"><i class="fas fa-check-double"></i> Check All</button>
    <button type="button" onclick="checkAll(false)" class="btn btn-outline btn-sm" style="color:var(--red);border-color:rgba(255,255,255,.2);background:rgba(255,255,255,.08);"><i class="fas fa-times"></i> Clear All</button>
  </div>
</div>

{{-- Progress bar --}}
<div style="background:#fff;border:1px solid var(--g200);border-radius:var(--r-sm);padding:12px 18px;margin-bottom:16px;display:flex;align-items:center;gap:14px;" class="anim-up d1">
  <span style="font-size:.78rem;color:var(--g500);font-weight:600;">Progress</span>
  <div style="flex:1;height:10px;background:var(--g100);border-radius:5px;overflow:hidden;">
    <div id="progressBar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--orange),var(--green));border-radius:5px;transition:width .4s;"></div>
  </div>
  <span id="progressText" style="font-family:var(--font-m);font-size:.82rem;font-weight:700;color:var(--g800);min-width:60px;text-align:right;">0 / 0</span>
</div>

<form method="POST" action="{{ route('checklists.store',$service) }}" id="checklistForm">
@csrf

<div style="display:grid;grid-template-columns:1fr 260px;gap:18px;">
  <div>
    @php $totalItems = $checklistGroups->flatten(1)->count(); $doneCount = 0; @endphp
    @foreach($checklistGroups as $group => $items)
    <div class="card anim-up d2" style="margin-bottom:14px;">
      <div class="card-header">
        <div class="card-title">{{ $group }}</div>
        <span class="badge badge-gray">{{ count($items) }} items</span>
      </div>
      <div class="card-body" style="padding:14px;">
        @foreach($items as $item)
        <div class="cl-item" id="item-{{ $item['key'] }}" onclick="toggleCheck(this,'{{ $item['key'] }}')">
          <input type="hidden" name="items[{{ $item['key'] }}][done]" value="0" id="h-{{ $item['key'] }}">
          <div class="cl-cb" id="cb-{{ $item['key'] }}"><i class="fas fa-check"></i></div>
          <span class="cl-name">{{ $item['label'] }}</span>
        </div>
        @endforeach
      </div>
    </div>
    @endforeach

    <div class="card anim-up">
      <div class="card-header"><div class="card-title"><i class="fas fa-sticky-note" style="color:var(--purple);margin-right:6px;"></i>Additional Notes</div></div>
      <div class="card-body">
        <textarea name="additional_notes" class="form-textarea" style="min-height:100px;" placeholder="Any extra observations, warnings, items needing follow-up…">{{ old('additional_notes') }}</textarea>
      </div>
    </div>
  </div>

  {{-- Sticky summary --}}
  <div style="position:sticky;top:84px;align-self:start;">
    <div class="card anim-up d3">
      <div class="card-header"><div class="card-title"><i class="fas fa-chart-simple" style="color:var(--orange);margin-right:6px;"></i>Summary</div></div>
      <div class="card-body" style="padding:16px;">
        <div style="text-align:center;margin-bottom:16px;">
          <div style="font-family:var(--font-m);font-size:2.5rem;font-weight:700;color:var(--navy);" id="doneCount">0</div>
          <div style="font-size:.72rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;">items checked</div>
        </div>
        <div style="height:8px;background:var(--g100);border-radius:4px;overflow:hidden;margin-bottom:14px;">
          <div id="summaryBar" style="height:100%;width:0%;background:linear-gradient(90deg,var(--orange),var(--green));border-radius:4px;transition:width .4s;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:.75rem;color:var(--g500);margin-bottom:16px;">
          <span>Total items</span><span style="font-weight:700;color:var(--g800);" id="totalCount">0</span>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
          <i class="fas fa-clipboard-check"></i> Save Checklist
        </button>
        <div style="margin-top:10px;font-size:.72rem;color:var(--g400);text-align:center;">Service status will change to <strong>In Progress</strong></div>
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
  document.getElementById('doneCount').textContent  = count;
  document.getElementById('totalCount').textContent = totalItems;
  document.getElementById('progressText').textContent = count+' / '+totalItems;
  document.getElementById('progressBar').style.width  = pct + '%';
  document.getElementById('summaryBar').style.width   = pct + '%';
}

updateUI();
</script>
@endpush
@endsection