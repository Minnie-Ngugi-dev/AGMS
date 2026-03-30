@extends('layouts.app')
@section('title','Register Vehicle')
@section('page-title','Register Vehicle')

@section('content')
<div style="max-width:760px;margin:0 auto;" class="anim-up">
  <div class="card">
    <div class="card-header">
      <div class="card-title"><i class="fas fa-car" style="color:var(--orange);margin-right:6px;"></i>Register New Vehicle</div>
      <a href="{{ route('vehicles.index') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('vehicles.store') }}">
        @csrf
        <div class="form-section">
          <div class="form-section-title"><i class="fas fa-user"></i> Owner</div>
          <div class="form-group">
            <label class="form-label">Customer <span class="req">*</span></label>
            <select name="customer_id" class="form-select" required>
              <option value="">— Select customer —</option>
              @foreach($customers as $c)
              <option value="{{ $c->id }}" {{ old('customer_id',request('customer'))==$c->id?'selected':'' }}>{{ $c->name }} — {{ $c->phone }}</option>
              @endforeach
            </select>
            @error('customer_id')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
            <div class="form-hint">Not listed? <a href="{{ route('customers.create') }}" style="color:var(--orange);">Add new customer first</a></div>
          </div>
        </div>

        <div class="form-section">
          <div class="form-section-title"><i class="fas fa-car"></i> Vehicle Details</div>
          <div class="form-grid" style="margin-bottom:14px;">
            <div class="form-group">
              <label class="form-label">Registration No <span class="req">*</span></label>
              <input type="text" name="registration_no" class="form-input" value="{{ old('registration_no') }}" placeholder="e.g. KBZ 123A" style="text-transform:uppercase;font-family:var(--font-m);font-weight:700;letter-spacing:1px;" oninput="this.value=this.value.toUpperCase()" required>
              @error('registration_no')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label class="form-label">Category <span class="req">*</span></label>
              <select name="category" class="form-select" required>
                <option value="">— Select —</option>
                @foreach(['Car','Van','Mini Truck','Truck','Trailer'] as $cat)
                <option value="{{ $cat }}" {{ old('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Make <span class="req">*</span></label>
              <select name="make" id="makeSelect" class="form-select" required onchange="populateModels(this.value)">
                <option value="">— Select make —</option>
                @foreach(['Toyota','Nissan','Mazda','Subaru','Honda','Mitsubishi','Isuzu','Mercedes-Benz','BMW','Volkswagen','Ford','Hyundai','Kia','Land Rover','Suzuki','Peugeot'] as $mk)
                <option value="{{ $mk }}" {{ old('make')===$mk?'selected':'' }}>{{ $mk }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Model <span class="req">*</span></label>
              <select name="model" id="modelSelect" class="form-select" required>
                <option value="">— Select make first —</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Year</label>
              <select name="year" class="form-select">
                <option value="">— Year —</option>
                @foreach(range(date('Y'),1990) as $yr)
                <option value="{{ $yr }}" {{ old('year')==$yr?'selected':'' }}>{{ $yr }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Color</label>
              <input type="text" name="color" class="form-input" value="{{ old('color') }}" placeholder="e.g. Silver">
            </div>
            <div class="form-group">
              <label class="form-label">Chassis Number</label>
              <input type="text" name="chassis_no" class="form-input" value="{{ old('chassis_no') }}" style="font-family:var(--font-m);" placeholder="VIN number">
            </div>
            <div class="form-group">
              <label class="form-label">Current Mileage <span class="req">*</span></label>
              <div class="input-group">
                <input type="number" name="current_mileage" class="form-input" value="{{ old('current_mileage') }}" placeholder="e.g. 45000" required>
                <span class="ig-addon">km</span>
              </div>
            </div>
          </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-top:16px;border-top:1px solid var(--g100);">
          <a href="{{ route('vehicles.index') }}" class="btn btn-outline">Cancel</a>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Register Vehicle</button>
        </div>
      </form>
    </div>
  </div>
</div>
@push('scripts')
<script>
const models={Toyota:['Corolla','Camry','Land Cruiser','Prado','Hilux','RAV4','Vitz','Wish','Fielder','Premio','Harrier','Hiace'],Nissan:['Note','March','X-Trail','Navara','Patrol','Tiida','Juke','Murano'],Mazda:['Demio','Axela','Atenza','CX-5','CX-3','BT-50'],Subaru:['Forester','Outback','Impreza','Legacy','XV','WRX'],Honda:['Fit','Jazz','Civic','Accord','CR-V','HR-V','Freed'],Mitsubishi:['Outlander','Pajero','L200','Galant','ASX','Colt'],Isuzu:['D-Max','NPS','FRR','FSR','TFR','MU-X'],'Mercedes-Benz':['C-Class','E-Class','S-Class','GLC','GLE','Vito','Sprinter'],BMW:['3 Series','5 Series','7 Series','X3','X5','X6'],Volkswagen:['Golf','Polo','Passat','Tiguan','Touareg','Caddy','Transporter'],Ford:['Ranger','Focus','Fiesta','Explorer','Everest'],Hyundai:['Tucson','Santa Fe','i10','i20','Accent','Elantra'],Kia:['Sportage','Sorento','Picanto','Rio','Cerato'],'Land Rover':['Discovery','Defender','Range Rover','Freelander','Evoque'],Suzuki:['Swift','SX4','Grand Vitara','Jimny','Alto'],Peugeot:['207','208','308','Partner','Boxer','3008']};
function populateModels(make){const sel=document.getElementById('modelSelect');sel.innerHTML='<option value="">— Select model —</option>';(models[make]||[]).forEach(m=>{const o=document.createElement('option');o.value=o.textContent=m;sel.appendChild(o);});}
</script>
@endpush
@endsection