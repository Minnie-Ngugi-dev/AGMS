@extends('layouts.app')
@section('title','System Settings')
@section('page-title','Admin — Settings')

@section('content')
<div style="max-width:800px;margin:0 auto;">
  <div style="display:flex;gap:8px;margin-bottom:20px;" class="anim-up">
    @foreach(['garage'=>['fas fa-building','Garage Info'],'billing'=>['fas fa-receipt','Billing & VAT'],'notifications'=>['fas fa-bell','Notifications'],'system'=>['fas fa-gear','System']] as $tab=>[$ico,$lbl])
    <button onclick="switchTab('{{ $tab }}')" id="tab-{{ $tab }}" style="padding:9px 16px;border-radius:var(--r-sm);font-size:.83rem;font-weight:600;cursor:pointer;border:1.5px solid;transition:all .18s;background:{{ $tab==='garage'?'var(--navy)':'#fff' }};border-color:{{ $tab==='garage'?'var(--navy)':'var(--g200)' }};color:{{ $tab==='garage'?'#fff':'var(--g600)' }};">
      <i class="{{ $ico }}" style="margin-right:5px;"></i>{{ $lbl }}
    </button>
    @endforeach
  </div>

  <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div id="panel-garage" class="card anim-up" style="margin-bottom:16px;">
      <div class="card-header"><div class="card-title"><i class="fas fa-building" style="color:var(--orange);margin-right:6px;"></i>Garage Information</div></div>
      <div class="card-body">
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group"><label class="form-label">Garage Name <span class="req">*</span></label><input type="text" name="garage_name" class="form-input" value="{{ $settings->garage_name }}" required></div>
          <div class="form-group"><label class="form-label">Phone</label><input type="tel" name="garage_phone" class="form-input" value="{{ $settings->garage_phone }}"></div>
          <div class="form-group"><label class="form-label">Email</label><input type="email" name="garage_email" class="form-input" value="{{ $settings->garage_email }}"></div>
          <div class="form-group"><label class="form-label">KRA PIN</label><input type="text" name="kra_pin" class="form-input" value="{{ $settings->kra_pin }}" placeholder="P051XXXXXXX" style="font-family:var(--font-m);"></div>
        </div>
        <div class="form-group" style="margin-bottom:14px;"><label class="form-label">Address</label><textarea name="garage_address" class="form-textarea" style="min-height:70px;">{{ $settings->garage_address }}</textarea></div>
        <div class="form-group">
          <label class="form-label">Logo</label>
          <input type="file" name="logo" class="form-input" accept="image/*">
          @if($settings->logo)<div style="margin-top:8px;"><img src="{{ Storage::url($settings->logo) }}" style="height:50px;border-radius:8px;border:1px solid var(--g200);"></div>@endif
        </div>
      </div>
    </div>

    <div id="panel-billing" class="card anim-up" style="margin-bottom:16px;display:none;">
      <div class="card-header"><div class="card-title"><i class="fas fa-receipt" style="color:var(--orange);margin-right:6px;"></i>Billing & VAT</div></div>
      <div class="card-body">
        <div class="form-grid" style="margin-bottom:14px;">
          <div class="form-group"><label class="form-label">Invoice Prefix</label><input type="text" name="invoice_prefix" class="form-input" value="{{ $settings->invoice_prefix??'INV' }}" placeholder="INV"></div>
          <div class="form-group"><label class="form-label">Starting Number</label><input type="number" name="invoice_start" class="form-input" value="{{ $settings->invoice_start??1001 }}" min="1"></div>
          <div class="form-group">
            <label class="form-label">Currency</label>
            <select name="currency" class="form-select">
              <option value="KSh" {{ ($settings->currency??'KSh')==='KSh'?'selected':'' }}>KSh — Kenyan Shilling</option>
              <option value="USD" {{ ($settings->currency??'')==='USD'?'selected':'' }}>USD — US Dollar</option>
              <option value="EUR" {{ ($settings->currency??'')==='EUR'?'selected':'' }}>EUR — Euro</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">VAT Rate (%)</label><div class="input-group"><input type="number" name="vat_rate" class="form-input" value="{{ $settings->vat_rate??16 }}" min="0" max="100" step="0.1"><span class="ig-addon">%</span></div></div>
        </div>
        <div style="background:var(--g50);border:1px solid var(--g200);border-radius:var(--r-sm);padding:14px 16px;display:flex;align-items:center;justify-content:space-between;">
          <div><div style="font-weight:600;color:var(--g800);font-size:.88rem;">Enable VAT</div><div style="font-size:.75rem;color:var(--g500);">Add VAT to all invoices</div></div>
          <label style="position:relative;display:inline-block;width:46px;height:26px;">
            <input type="checkbox" name="vat_enabled" value="1" {{ $settings->vat_enabled?'checked':'' }} style="opacity:0;width:0;height:0;">
            <span onclick="this.previousElementSibling.click();this.style.background=this.previousElementSibling.checked?'var(--green)':'var(--g300)';" style="position:absolute;cursor:pointer;inset:0;background:{{ $settings->vat_enabled?'var(--green)':'var(--g300)' }};border-radius:26px;transition:.3s;"><span style="position:absolute;height:20px;width:20px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;{{ $settings->vat_enabled?'transform:translateX(20px);':'' }}"></span></span>
          </label>
        </div>
      </div>
    </div>

    <div id="panel-notifications" class="card anim-up" style="margin-bottom:16px;display:none;">
      <div class="card-header"><div class="card-title"><i class="fas fa-bell" style="color:var(--orange);margin-right:6px;"></i>Notifications</div></div>
      <div class="card-body">
        @foreach([['Regular service reminder','notify_regular','SMS when regular service is due'],['Full service reminder','notify_full','SMS when full service is due'],['Service complete','notify_complete','Notify customer when vehicle is ready'],['Overdue alert','notify_overdue','Alert overdue customers']] as [$lbl,$name,$desc])
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 0;border-bottom:1px solid var(--g100);">
          <div><div style="font-weight:600;color:var(--g800);font-size:.85rem;">{{ $lbl }}</div><div style="font-size:.74rem;color:var(--g500);">{{ $desc }}</div></div>
          <label style="position:relative;display:inline-block;width:46px;height:26px;flex-shrink:0;">
            <input type="checkbox" name="{{ $name }}" value="1" {{ ($settings->$name??false)?'checked':'' }} style="opacity:0;width:0;height:0;">
            <span onclick="this.previousElementSibling.click();this.style.background=this.previousElementSibling.checked?'var(--green)':'var(--g300)';" style="position:absolute;cursor:pointer;inset:0;background:{{ ($settings->$name??false)?'var(--green)':'var(--g300)' }};border-radius:26px;transition:.3s;"><span style="position:absolute;height:20px;width:20px;left:3px;bottom:3px;background:#fff;border-radius:50%;transition:.3s;{{ ($settings->$name??false)?'transform:translateX(20px);':'' }}"></span></span>
          </label>
        </div>
        @endforeach
      </div>
    </div>

    <div id="panel-system" class="card anim-up" style="margin-bottom:16px;display:none;">
      <div class="card-header"><div class="card-title"><i class="fas fa-gear" style="color:var(--orange);margin-right:6px;"></i>System Settings</div></div>
      <div class="card-body">
        <div class="form-grid">
          <div class="form-group"><label class="form-label">Regular Interval (km)</label><div class="input-group"><input type="number" name="regular_km" class="form-input" value="{{ $settings->regular_km??5000 }}"><span class="ig-addon">km</span></div><div class="form-hint">Default: 5,000 km</div></div>
          <div class="form-group"><label class="form-label">Regular Interval (days)</label><div class="input-group"><input type="number" name="regular_days" class="form-input" value="{{ $settings->regular_days??90 }}"><span class="ig-addon">days</span></div><div class="form-hint">Default: 90 days</div></div>
          <div class="form-group"><label class="form-label">Full Service Interval (km)</label><div class="input-group"><input type="number" name="full_km" class="form-input" value="{{ $settings->full_km??10000 }}"><span class="ig-addon">km</span></div><div class="form-hint">Default: 10,000 km</div></div>
          <div class="form-group"><label class="form-label">Full Service Interval (days)</label><div class="input-group"><input type="number" name="full_days" class="form-input" value="{{ $settings->full_days??180 }}"><span class="ig-addon">days</span></div><div class="form-hint">Default: 180 days</div></div>
        </div>
      </div>
    </div>

    <div style="display:flex;justify-content:flex-end;" class="anim-up">
      <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> Save Settings</button>
    </div>
  </form>
</div>

@push('scripts')
<script>
function switchTab(tab) {
  ['garage','billing','notifications','system'].forEach(t => {
    document.getElementById('panel-'+t).style.display = t===tab ? '' : 'none';
    const btn = document.getElementById('tab-'+t);
    if(t===tab){btn.style.background='var(--navy)';btn.style.borderColor='var(--navy)';btn.style.color='#fff';}
    else{btn.style.background='#fff';btn.style.borderColor='var(--g200)';btn.style.color='var(--g600)';}
  });
}
</script>
@endpush
@endsection