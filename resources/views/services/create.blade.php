@extends('layouts.app')
@section('title','New Service')
@section('page-title','New Service')

@section('content')
<div style="max-width:780px;margin:0 auto;" class="anim-up">

{{-- Step indicator --}}
<div style="display:flex;align-items:center;gap:0;margin-bottom:24px;background:#fff;border:1px solid var(--g200);border-radius:var(--r);padding:16px 20px;box-shadow:var(--sh);">
  @foreach(['Customer','Vehicle','Service Items','Confirm'] as $i => $step)
  <div style="display:flex;align-items:center;flex:1;">
    <div style="display:flex;align-items:center;gap:8px;">
      <div id="step-circle-{{ $i+1 }}" style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:700;background:{{ $i===0?'var(--orange)':'var(--g200)' }};color:{{ $i===0?'#fff':'var(--g500)' }};transition:all .3s;">{{ $i+1 }}</div>
      <span id="step-label-{{ $i+1 }}" style="font-size:.78rem;font-weight:{{ $i===0?'700':'500' }};color:{{ $i===0?'var(--g800)':'var(--g400)' }};transition:all .3s;">{{ $step }}</span>
    </div>
    @if($i < 3)
    <div style="flex:1;height:2px;background:var(--g200);margin:0 8px;" id="step-line-{{ $i+1 }}"></div>
    @endif
  </div>
  @endforeach
</div>

<form method="POST" action="{{ route('services.store') }}" id="serviceForm">
@csrf

{{-- ── STEP 1: CUSTOMER ── --}}
<div id="section-1" class="card" style="margin-bottom:16px;">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-user" style="color:var(--orange);margin-right:8px;"></i> Step 1 — Customer</div>
  </div>
  <div class="card-body">

    {{-- New or Returning toggle --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
      <label onclick="setCustomerType('new')" style="cursor:pointer;">
        <input type="radio" name="_customer_type" value="new" style="display:none;" checked>
        <div id="typeNew" style="border:2px solid var(--orange);background:var(--orange-l);border-radius:var(--r);padding:14px;text-align:center;transition:all .2s;">
          <i class="fas fa-user-plus" style="font-size:1.2rem;color:var(--orange);margin-bottom:6px;display:block;"></i>
          <div style="font-weight:700;color:var(--orange);font-size:.88rem;">New Customer</div>
          <div style="font-size:.7rem;color:var(--g500);margin-top:2px;">First visit</div>
        </div>
      </label>
      <label onclick="setCustomerType('returning')" style="cursor:pointer;">
        <input type="radio" name="_customer_type" value="returning" style="display:none;">
        <div id="typeReturning" style="border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:14px;text-align:center;transition:all .2s;">
          <i class="fas fa-user-check" style="font-size:1.2rem;color:var(--g400);margin-bottom:6px;display:block;"></i>
          <div style="font-weight:700;color:var(--g600);font-size:.88rem;">Returning Customer</div>
          <div style="font-size:.7rem;color:var(--g500);margin-top:2px;">Already registered</div>
        </div>
      </label>
    </div>

    {{-- Returning: search --}}
    <div id="returningSearch" style="display:none;margin-bottom:16px;">
      <label class="form-label"><i class="fas fa-search" style="color:var(--orange);margin-right:5px;"></i>Search by Phone or Plate Number</label>
      <input type="text" id="customerSearch" class="form-input"
        placeholder="Type phone number or plate e.g. KCA 123A or 0712345678"
        oninput="searchCustomer(this.value)" autocomplete="off">
      <div id="searchDropdown" style="display:none;position:absolute;z-index:100;background:#fff;border:1.5px solid var(--g200);border-radius:var(--r-sm);box-shadow:var(--sh-md);width:100%;max-height:200px;overflow-y:auto;margin-top:4px;"></div>
    </div>

    {{-- Customer found banner --}}
    <div id="customerFoundBanner" style="display:none;background:var(--green-l);border:1.5px solid #86efac;border-radius:var(--r-sm);padding:12px 14px;margin-bottom:16px;display:none;align-items:center;gap:10px;">
      <i class="fas fa-circle-check" style="color:var(--green);font-size:1rem;"></i>
      <div>
        <div style="font-weight:700;color:var(--green);font-size:.88rem;" id="customerFoundName">—</div>
        <div style="font-size:.72rem;color:var(--g500);" id="customerFoundDetails">—</div>
      </div>
      <button type="button" onclick="clearCustomer()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:var(--g400);font-size:.8rem;">
        <i class="fas fa-times"></i> Change
      </button>
    </div>

    {{-- Customer fields --}}
    <div id="customerFields">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Full Name <span class="req">*</span></label>
          <input type="text" name="customer_name" id="customerName" class="form-input"
            value="{{ old('customer_name') }}" placeholder="John Kamau" required>
          @error('customer_name')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Phone Number <span class="req">*</span></label>
          <input type="tel" name="customer_phone" id="customerPhone" class="form-input"
            value="{{ old('customer_phone') }}" placeholder="07XX XXX XXX" required>
          @error('customer_phone')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="customer_email" id="customerEmail" class="form-input"
            value="{{ old('customer_email') }}" placeholder="optional">
        </div>
        <div class="form-group">
          <label class="form-label">Address</label>
          <input type="text" name="customer_address" id="customerAddress" class="form-input"
            value="{{ old('customer_address') }}" placeholder="optional">
        </div>
      </div>
    </div>

    <input type="hidden" name="customer_id" id="customerId">
  </div>
  <div class="card-footer" style="display:flex;justify-content:flex-end;">
    <button type="button" onclick="goToStep(2)" class="btn btn-primary">
      Next: Vehicle <i class="fas fa-arrow-right"></i>
    </button>
  </div>
</div>

{{-- ── STEP 2: VEHICLE ── --}}
<div id="section-2" class="card" style="margin-bottom:16px;display:none;">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-car" style="color:var(--orange);margin-right:8px;"></i> Step 2 — Vehicle</div>
  </div>
  <div class="card-body">

    {{-- Returning customer: vehicle selector --}}
    <div id="vehicleSelector" style="display:none;margin-bottom:16px;">
      <label class="form-label">Select Vehicle</label>
      <div id="vehicleCards" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:10px;margin-bottom:12px;"></div>
      <button type="button" onclick="showNewVehicleForm()" class="btn btn-outline btn-sm">
        <i class="fas fa-plus"></i> Add New Vehicle for this Customer
      </button>
    </div>

    {{-- Vehicle form --}}
    <div id="vehicleForm">
      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">Registration Number <span class="req">*</span></label>
          <input type="text" name="registration_no" id="regNo" class="form-input"
            value="{{ old('registration_no') }}" placeholder="KCA 123A"
            style="font-family:var(--font-m);text-transform:uppercase;" required
            oninput="this.value=this.value.toUpperCase()">
          @error('registration_no')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
          <label class="form-label">Category <span class="req">*</span></label>
          <select name="category" id="vehicleCategory" class="form-select" required>
            <option value="">Select...</option>
            @foreach(['Car','Van','Mini Truck','Truck','Trailer'] as $cat)
            <option value="{{ $cat }}" {{ old('category')==$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Make <span class="req">*</span></label>
          <input type="text" name="make" id="vehicleMake" class="form-input"
            value="{{ old('make') }}" placeholder="e.g. Toyota" required
            list="makes-list" oninput="loadModels(this.value)">
          <datalist id="makes-list">
            @foreach($vehicleMakes as $make)
            <option value="{{ $make->name }}">
            @endforeach
          </datalist>
        </div>
        <div class="form-group">
          <label class="form-label">Model <span class="req">*</span></label>
          <input type="text" name="model" id="vehicleModel" class="form-input"
            value="{{ old('model') }}" placeholder="e.g. Corolla" required list="models-list">
          <datalist id="models-list"></datalist>
        </div>
        <div class="form-group">
          <label class="form-label">Year</label>
          <input type="number" name="year" class="form-input" value="{{ old('year') }}"
            placeholder="e.g. 2019" min="1970" max="{{ date('Y')+1 }}">
        </div>
        <div class="form-group">
          <label class="form-label">Color</label>
          <input type="text" name="color" class="form-input" value="{{ old('color') }}" placeholder="e.g. Silver">
        </div>
        <div class="form-group">
          <label class="form-label">Current Mileage (km)</label>
          <input type="number" name="mileage_in" id="mileageIn" class="form-input"
            value="{{ old('mileage_in') }}" placeholder="e.g. 45000">
        </div>
        <div class="form-group">
          <label class="form-label">Chassis Number</label>
          <input type="text" name="chassis_no" class="form-input"
            value="{{ old('chassis_no') }}" placeholder="optional"
            style="font-family:var(--font-m);text-transform:uppercase;"
            oninput="this.value=this.value.toUpperCase()">
        </div>
      </div>
    </div>

    <input type="hidden" name="vehicle_id" id="vehicleId">
  </div>
  <div class="card-footer" style="display:flex;justify-content:space-between;">
    <button type="button" onclick="goToStep(1)" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</button>
    <button type="button" onclick="goToStep(3)" class="btn btn-primary">Next: Service Items <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

{{-- ── STEP 3: SERVICE ITEMS ── --}}
<div id="section-3" class="card" style="margin-bottom:16px;display:none;">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-list-check" style="color:var(--orange);margin-right:8px;"></i> Step 3 — What needs to be done?</div>
  </div>
  <div class="card-body">

    {{-- Service type --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
      <label onclick="selectServiceType('Regular')" style="cursor:pointer;">
        <input type="radio" name="service_type" value="Regular" style="display:none;" checked>
        <div id="stRegular" style="border:2px solid var(--sky);background:var(--sky-l);border-radius:var(--r);padding:12px;text-align:center;transition:all .2s;">
          <i class="fas fa-oil-can" style="font-size:1.1rem;color:var(--sky);margin-bottom:5px;display:block;"></i>
          <div style="font-weight:700;color:var(--sky);font-size:.85rem;">Regular Service</div>
          <div style="font-size:.68rem;color:var(--g500);">5,000 km / 3 months</div>
        </div>
      </label>
      <label onclick="selectServiceType('Full')" style="cursor:pointer;">
        <input type="radio" name="service_type" value="Full" style="display:none;">
        <div id="stFull" style="border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:12px;text-align:center;transition:all .2s;">
          <i class="fas fa-car-battery" style="font-size:1.1rem;color:var(--g400);margin-bottom:5px;display:block;"></i>
          <div style="font-weight:700;color:var(--g600);font-size:.85rem;">Full Service</div>
          <div style="font-size:.68rem;color:var(--g500);">10,000 km / 6 months</div>
        </div>
      </label>
    </div>

    {{-- Service items by category from DB --}}
    @forelse($serviceItemsByCategory as $category => $items)
    <div style="margin-bottom:18px;">
      <div style="font-size:.68rem;font-weight:700;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;padding-bottom:6px;border-bottom:1px solid var(--g100);">
        <i class="fas fa-tag" style="color:var(--orange);margin-right:5px;"></i> {{ $category }}
      </div>
      <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:8px;">
        @foreach($items as $item)
        <label style="cursor:pointer;">
          <input type="checkbox" name="service_item_ids[]" value="{{ $item->id }}"
            data-price="{{ $item->price }}" data-name="{{ $item->name }}"
            style="display:none;" class="service-item-cb" onchange="updateOrderTotal()">
          <div class="si-card" data-id="{{ $item->id }}"
            style="border:1.5px solid var(--g200);border-radius:var(--r-sm);padding:10px 12px;display:flex;align-items:center;justify-content:space-between;gap:8px;transition:all .18s;background:#fff;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="si-check" style="width:20px;height:20px;border-radius:5px;border:2px solid var(--g300);display:flex;align-items:center;justify-content:center;font-size:.65rem;color:transparent;transition:all .18s;flex-shrink:0;"></div>
              <span style="font-size:.83rem;font-weight:500;color:var(--g700);">{{ $item->name }}</span>
            </div>
            <span style="font-family:var(--font-m);font-size:.78rem;font-weight:700;color:var(--green);flex-shrink:0;">KSh {{ number_format($item->price,0) }}</span>
          </div>
        </label>
        @endforeach
      </div>
    </div>
    @empty
    <div class="empty-state">
      <div class="empty-icon"><i class="fas fa-list-check"></i></div>
      <div class="empty-title">No service items configured</div>
      <div class="empty-sub">Ask admin to add service items first.</div>
      <a href="{{ route('admin.service-items.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Add Service Items</a>
    </div>
    @endforelse

    {{-- Customer complaint --}}
    <div class="form-group" style="margin-top:16px;margin-bottom:16px;">
      <label class="form-label">Customer Complaint / Additional Notes</label>
      <textarea name="customer_complaint" class="form-textarea"
        placeholder="Describe any specific issues the customer reported...">{{ old('customer_complaint') }}</textarea>
    </div>

    {{-- Driver info --}}
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Driver Name</label>
        <input type="text" name="driver_name" class="form-input" value="{{ old('driver_name') }}" placeholder="If different from owner">
      </div>
      <div class="form-group">
        <label class="form-label">Driver Phone</label>
        <input type="tel" name="driver_phone" class="form-input" value="{{ old('driver_phone') }}" placeholder="07XX XXX XXX">
      </div>
    </div>

    {{-- Order total --}}
    <div id="orderTotalBar" style="display:none;background:var(--navy);color:#fff;border-radius:var(--r-sm);padding:12px 16px;margin-top:14px;display:flex;justify-content:space-between;align-items:center;">
      <span style="font-size:.82rem;"><i class="fas fa-list" style="margin-right:6px;"></i><span id="selectedCount">0</span> items selected</span>
      <span style="font-family:var(--font-m);font-size:1rem;font-weight:700;color:var(--orange);">KSh <span id="orderTotal">0</span></span>
    </div>
  </div>
  <div class="card-footer" style="display:flex;justify-content:space-between;">
    <button type="button" onclick="goToStep(2)" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</button>
    <button type="button" onclick="goToStep(4)" class="btn btn-primary">Review & Confirm <i class="fas fa-arrow-right"></i></button>
  </div>
</div>

{{-- ── STEP 4: CONFIRM ── --}}
<div id="section-4" class="card" style="display:none;">
  <div class="card-header">
    <div class="card-title"><i class="fas fa-check-circle" style="color:var(--green);margin-right:8px;"></i> Step 4 — Review & Confirm</div>
  </div>
  <div class="card-body">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px;">
      <div style="background:var(--g50);border-radius:var(--r-sm);padding:14px;">
        <div style="font-size:.65rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Customer</div>
        <div id="confirmCustomer" style="font-size:.85rem;color:var(--g700);line-height:1.7;"></div>
      </div>
      <div style="background:var(--g50);border-radius:var(--r-sm);padding:14px;">
        <div style="font-size:.65rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Vehicle</div>
        <div id="confirmVehicle" style="font-size:.85rem;color:var(--g700);line-height:1.7;"></div>
      </div>
    </div>
    <div style="background:var(--g50);border-radius:var(--r-sm);padding:14px;margin-bottom:16px;">
      <div style="font-size:.65rem;color:var(--g400);text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;">Services Selected</div>
      <div id="confirmItems" style="font-size:.85rem;color:var(--g700);"></div>
    </div>

    {{-- Date + Mechanic --}}
    <div class="form-grid">
      <div class="form-group">
        <label class="form-label">Service Date <span class="req">*</span></label>
        <input type="date" name="service_date" class="form-input" value="{{ old('service_date', date('Y-m-d')) }}" required>
      </div>
      <div class="form-group">
        <label class="form-label">Assign Mechanic</label>
        <select name="mechanic_id" class="form-select">
          <option value="">— Assign later —</option>
          @foreach($mechanics as $m)
          <option value="{{ $m->id }}" {{ old('mechanic_id')==$m->id?'selected':'' }}>{{ $m->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Estimated Completion</label>
        <input type="date" name="estimated_completion" class="form-input" value="{{ old('estimated_completion') }}">
      </div>
      <div class="form-group">
        <label class="form-label">Labour Charge (KSh)</label>
        <div class="input-group">
          <span class="ig-addon">KSh</span>
          <input type="number" name="labour_charge" class="form-input"
            style="border-radius:0 var(--r-sm) var(--r-sm) 0;border-left:none;"
            value="{{ old('labour_charge',0) }}" min="0" step="0.01">
        </div>
      </div>
    </div>
  </div>
  <div class="card-footer" style="display:flex;justify-content:space-between;">
    <button type="button" onclick="goToStep(3)" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back</button>
    <button type="submit" class="btn btn-green btn-lg">
      <i class="fas fa-check"></i> Create Service Job Card
    </button>
  </div>
</div>

</form>
</div>

@push('scripts')
<script>
let currentStep = 1;
const vehicleMakesData = @json($vehicleMakes->keyBy('name'));

// ── STEP NAVIGATION ────────────────────────────────────────────
function goToStep(step) {
  // Validate before going forward
  if (step > currentStep) {
    if (currentStep === 1 && !validateStep1()) return;
    if (currentStep === 2 && !validateStep2()) return;
  }

  document.getElementById(`section-${currentStep}`).style.display = 'none';
  document.getElementById(`section-${step}`).style.display = 'block';
  currentStep = step;

  // Update step indicators
  for (let i = 1; i <= 4; i++) {
    const circle = document.getElementById(`step-circle-${i}`);
    const label  = document.getElementById(`step-label-${i}`);
    if (i < step) {
      circle.style.background = 'var(--green)';
      circle.style.color = '#fff';
      circle.innerHTML = '<i class="fas fa-check" style="font-size:.6rem;"></i>';
    } else if (i === step) {
      circle.style.background = 'var(--orange)';
      circle.style.color = '#fff';
      circle.textContent = i;
      label.style.fontWeight = '700';
      label.style.color = 'var(--g800)';
    } else {
      circle.style.background = 'var(--g200)';
      circle.style.color = 'var(--g500)';
      circle.textContent = i;
      label.style.fontWeight = '500';
      label.style.color = 'var(--g400)';
    }
  }

  if (step === 4) buildConfirmPage();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function validateStep1() {
  const name  = document.getElementById('customerName').value.trim();
  const phone = document.getElementById('customerPhone').value.trim();
  const cid   = document.getElementById('customerId').value;

  if (!cid && (!name || !phone)) {
    alert('Please fill in customer name and phone number.');
    return false;
  }
  return true;
}

function validateStep2() {
  const reg = document.getElementById('regNo').value.trim();
  const vid = document.getElementById('vehicleId').value;
  if (!vid && !reg) {
    alert('Please enter a registration number.');
    return false;
  }
  return true;
}

// ── CUSTOMER TYPE ──────────────────────────────────────────────
function setCustomerType(type) {
  const isReturning = type === 'returning';

  document.getElementById('typeNew').style.cssText      = isReturning ? 'border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:14px;text-align:center;transition:all .2s;' : 'border:2px solid var(--orange);background:var(--orange-l);border-radius:var(--r);padding:14px;text-align:center;transition:all .2s;';
  document.getElementById('typeReturning').style.cssText = isReturning ? 'border:2px solid var(--green);background:var(--green-l);border-radius:var(--r);padding:14px;text-align:center;transition:all .2s;' : 'border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:14px;text-align:center;transition:all .2s;';

  document.getElementById('returningSearch').style.display  = isReturning ? 'block' : 'none';
  document.getElementById('customerFields').style.display   = isReturning ? 'none' : 'block';
  document.getElementById('customerFoundBanner').style.display = 'none';

  if (!isReturning) {
    clearCustomer();
  }
}

// ── CUSTOMER SEARCH ────────────────────────────────────────────
let searchTimer;
async function searchCustomer(query) {
  if (query.length < 2) {
    document.getElementById('searchDropdown').style.display = 'none';
    return;
  }

  clearTimeout(searchTimer);
  searchTimer = setTimeout(async () => {
    try {
      const res  = await fetch(`/search/customer?q=${encodeURIComponent(query)}`);
      const data = await res.json();
      const dd   = document.getElementById('searchDropdown');

      if (!data.length) {
        dd.innerHTML = '<div style="padding:12px 14px;font-size:.82rem;color:var(--g400);">No customer found</div>';
        dd.style.display = 'block';
        return;
      }

      dd.innerHTML = data.map(c => `
        <div onclick='selectCustomer(${JSON.stringify(c)})'
          style="padding:11px 14px;cursor:pointer;border-bottom:1px solid var(--g100);transition:background .15s;"
          onmouseover="this.style.background='var(--g50)'" onmouseout="this.style.background='#fff'">
          <div style="font-weight:600;font-size:.85rem;color:var(--g800);">${c.name}</div>
          <div style="font-size:.72rem;color:var(--g400);">${c.phone} · ${c.vehicles_count} vehicle(s)</div>
        </div>`).join('');
      dd.style.display = 'block';
    } catch(e) {}
  }, 300);
}

function selectCustomer(customer) {
  document.getElementById('customerId').value      = customer.id;
  document.getElementById('customerName').value    = customer.name;
  document.getElementById('customerPhone').value   = customer.phone;
  document.getElementById('customerEmail').value   = customer.email || '';
  document.getElementById('customerAddress').value = customer.address || '';

  document.getElementById('customerFoundName').textContent    = customer.name;
  document.getElementById('customerFoundDetails').textContent = `${customer.phone} · ${customer.vehicles_count} registered vehicle(s)`;
  document.getElementById('customerFoundBanner').style.display = 'flex';
  document.getElementById('returningSearch').style.display    = 'none';
  document.getElementById('searchDropdown').style.display     = 'none';

  // Load their vehicles for step 2
  loadCustomerVehicles(customer.id);
}

function clearCustomer() {
  document.getElementById('customerId').value              = '';
  document.getElementById('customerName').value            = '';
  document.getElementById('customerPhone').value           = '';
  document.getElementById('customerEmail').value           = '';
  document.getElementById('customerAddress').value         = '';
  document.getElementById('customerFoundBanner').style.display = 'none';
  document.getElementById('returningSearch').style.display = 'block';
  document.getElementById('customerSearch').value          = '';
  document.getElementById('vehicleSelector').style.display = 'none';
  document.getElementById('vehicleForm').style.display     = 'block';
}

async function loadCustomerVehicles(customerId) {
  try {
    const res     = await fetch(`/customers/${customerId}/vehicles-json`);
    const vehicles = await res.json();

    if (vehicles.length) {
      const container = document.getElementById('vehicleCards');
      container.innerHTML = vehicles.map(v => `
        <div onclick='selectVehicle(${JSON.stringify(v)})'
          class="vehicle-option-card"
          id="vcard-${v.id}"
          style="border:1.5px solid var(--g200);border-radius:var(--r-sm);padding:12px;cursor:pointer;transition:all .18s;background:#fff;">
          <div style="font-family:var(--font-m);font-weight:700;color:var(--orange);font-size:.9rem;">${v.registration_no}</div>
          <div style="font-size:.78rem;color:var(--g600);margin-top:3px;">${v.year||''} ${v.make} ${v.model}</div>
          <div style="font-size:.7rem;color:var(--g400);margin-top:2px;">${v.category} · ${v.color||''}</div>
        </div>`).join('');

      document.getElementById('vehicleSelector').style.display = 'block';
      document.getElementById('vehicleForm').style.display     = 'none';
    }
  } catch(e) {}
}

function selectVehicle(vehicle) {
  document.getElementById('vehicleId').value       = vehicle.id;
  document.getElementById('regNo').value           = vehicle.registration_no;
  document.getElementById('vehicleMake').value     = vehicle.make;
  document.getElementById('vehicleModel').value    = vehicle.model;
  document.getElementById('vehicleCategory').value = vehicle.category;

  // Highlight selected card
  document.querySelectorAll('.vehicle-option-card').forEach(c => {
    c.style.border = '1.5px solid var(--g200)';
    c.style.background = '#fff';
  });
  const card = document.getElementById(`vcard-${vehicle.id}`);
  if (card) {
    card.style.border = '1.5px solid var(--orange)';
    card.style.background = 'var(--orange-l)';
  }
  document.getElementById('vehicleForm').style.display = 'block';
}

function showNewVehicleForm() {
  document.getElementById('vehicleId').value       = '';
  document.getElementById('regNo').value           = '';
  document.getElementById('vehicleMake').value     = '';
  document.getElementById('vehicleModel').value    = '';
  document.getElementById('vehicleForm').style.display = 'block';
  document.querySelectorAll('.vehicle-option-card').forEach(c => {
    c.style.border = '1.5px solid var(--g200)';
    c.style.background = '#fff';
  });
}

// ── VEHICLE MAKE → MODELS ──────────────────────────────────────
function loadModels(makeName) {
  const make   = vehicleMakesData[makeName];
  const dl     = document.getElementById('models-list');
  dl.innerHTML = '';
  if (make && make.models) {
    make.models.forEach(m => {
      const opt = document.createElement('option');
      opt.value = m;
      dl.appendChild(opt);
    });
  }
}

// ── SERVICE TYPE ───────────────────────────────────────────────
function selectServiceType(type) {
  const isRegular = type === 'Regular';
  document.getElementById('stRegular').style.cssText = isRegular
    ? 'border:2px solid var(--sky);background:var(--sky-l);border-radius:var(--r);padding:12px;text-align:center;transition:all .2s;'
    : 'border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:12px;text-align:center;transition:all .2s;';
  document.getElementById('stFull').style.cssText = !isRegular
    ? 'border:2px solid var(--purple);background:var(--purple-l);border-radius:var(--r);padding:12px;text-align:center;transition:all .2s;'
    : 'border:2px solid var(--g200);background:#fff;border-radius:var(--r);padding:12px;text-align:center;transition:all .2s;';
  document.querySelector(`input[name="service_type"][value="${type}"]`).checked = true;
}

// ── SERVICE ITEMS TOTAL ────────────────────────────────────────
function updateOrderTotal() {
  let total = 0;
  let count = 0;
  document.querySelectorAll('.service-item-cb:checked').forEach(cb => {
    total += parseFloat(cb.dataset.price) || 0;
    count++;
  });

  // Update card styles
  document.querySelectorAll('.service-item-cb').forEach(cb => {
    const card  = cb.closest('label').querySelector('.si-card');
    const check = card.querySelector('.si-check');
    if (cb.checked) {
      card.style.border = '1.5px solid var(--orange)';
      card.style.background = 'var(--orange-l)';
      check.style.background = 'var(--orange)';
      check.style.borderColor = 'var(--orange)';
      check.style.color = '#fff';
      check.innerHTML = '<i class="fas fa-check"></i>';
    } else {
      card.style.border = '1.5px solid var(--g200)';
      card.style.background = '#fff';
      check.style.background = 'transparent';
      check.style.borderColor = 'var(--g300)';
      check.style.color = 'transparent';
      check.innerHTML = '';
    }
  });

  document.getElementById('selectedCount').textContent = count;
  document.getElementById('orderTotal').textContent    = new Intl.NumberFormat().format(total);
  document.getElementById('orderTotalBar').style.display = count > 0 ? 'flex' : 'none';
}

// ── CONFIRM PAGE ───────────────────────────────────────────────
function buildConfirmPage() {
  const name    = document.getElementById('customerName').value;
  const phone   = document.getElementById('customerPhone').value;
  const reg     = document.getElementById('regNo').value;
  const make    = document.getElementById('vehicleMake').value;
  const model   = document.getElementById('vehicleModel').value;
  const cat     = document.getElementById('vehicleCategory').value;

  document.getElementById('confirmCustomer').innerHTML =
    `<strong>${name}</strong><br>${phone}`;

  document.getElementById('confirmVehicle').innerHTML =
    `<strong style="font-family:var(--font-m);color:var(--orange);">${reg}</strong><br>${make} ${model}<br>${cat}`;

  const selected = [];
  let total = 0;
  document.querySelectorAll('.service-item-cb:checked').forEach(cb => {
    selected.push(`<div style="display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid var(--g100);">
      <span>${cb.dataset.name}</span>
      <span style="font-family:var(--font-m);font-weight:700;color:var(--green);">KSh ${new Intl.NumberFormat().format(cb.dataset.price)}</span>
    </div>`);
    total += parseFloat(cb.dataset.price) || 0;
  });

  document.getElementById('confirmItems').innerHTML = selected.length
    ? selected.join('') + `<div style="display:flex;justify-content:space-between;padding:8px 0;font-weight:700;color:var(--navy);">
        <span>Estimated Total</span>
        <span style="font-family:var(--font-m);color:var(--orange);">KSh ${new Intl.NumberFormat().format(total)}</span>
      </div>`
    : '<span style="color:var(--g400);font-size:.82rem;">No service items selected — can be added later.</span>';
}
</script>
@endpush
@endsection