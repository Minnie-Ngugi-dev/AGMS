<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register — AGMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --orange:#FF6B2C; --navy:#0F2040; --green:#16A34A; --red:#DC2626;
  --font-h:'Space Grotesk',sans-serif; --font-b:'DM Sans',sans-serif;
  --r:12px; --r-sm:8px;
  --bg:#F8FAFC; --card:#ffffff; --border:#E2E8F0;
  --text:#0F172A; --text-sub:#64748B; --input-bg:#F8FAFC;
  --shadow:0 20px 60px rgba(0,0,0,.08);
}
html.dark {
  --bg:#0A0F1E; --card:#111827; --border:#1F2937;
  --text:#F1F5F9; --text-sub:#94A3B8; --input-bg:#1F2937;
  --shadow:0 20px 60px rgba(0,0,0,.4);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--font-b);background:var(--bg);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;transition:background .3s;}
.bg-shapes{position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0;}
.shape{position:absolute;border-radius:50%;filter:blur(80px);opacity:.3;animation:float 8s ease-in-out infinite;}
.shape-1{width:350px;height:350px;background:var(--orange);top:-80px;right:-80px;animation-delay:0s;}
.shape-2{width:250px;height:250px;background:var(--navy);bottom:-60px;left:-60px;animation-delay:2s;}
html.dark .shape{opacity:.12;}
@keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-25px);}}
.card{background:var(--card);border:1px solid var(--border);border-radius:20px;box-shadow:var(--shadow);width:100%;max-width:460px;padding:38px;position:relative;z-index:1;animation:slideUp .5s ease both;}
@keyframes slideUp{from{opacity:0;transform:translateY(20px);}to{opacity:1;transform:translateY(0);}}
.logo{display:flex;align-items:center;gap:12px;margin-bottom:28px;}
.logo-icon{width:44px;height:44px;background:var(--orange);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#fff;box-shadow:0 6px 20px rgba(255,107,44,.35);}
.logo-text{font-family:var(--font-h);font-size:1.2rem;font-weight:700;color:var(--text);}
.logo-sub{font-size:.65rem;color:var(--text-sub);letter-spacing:1.5px;text-transform:uppercase;}
.title{font-family:var(--font-h);font-size:1.35rem;font-weight:700;color:var(--text);margin-bottom:4px;}
.subtitle{font-size:.82rem;color:var(--text-sub);margin-bottom:24px;}
.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
.form-group{margin-bottom:14px;}
.form-group.full{grid-column:1/-1;}
.form-label{display:block;font-size:.76rem;font-weight:600;color:var(--text);margin-bottom:5px;}
.form-input{width:100%;padding:10px 13px;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.85rem;font-family:var(--font-b);color:var(--text);background:var(--input-bg);transition:all .2s;outline:none;}
.form-input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(255,107,44,.1);background:var(--card);}
.input-wrap{position:relative;}
.input-wrap .form-input{padding-right:40px;}
.toggle-pass{position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-sub);font-size:.82rem;padding:4px;transition:color .2s;}
.toggle-pass:hover{color:var(--orange);}
.form-error{font-size:.72rem;color:var(--red);margin-top:4px;display:flex;align-items:center;gap:4px;}
.btn-register{width:100%;padding:12px;background:var(--orange);color:#fff;border:none;border-radius:var(--r-sm);font-size:.9rem;font-weight:700;font-family:var(--font-h);cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-top:6px;margin-bottom:18px;}
.btn-register:hover{background:#E55A1E;box-shadow:0 8px 24px rgba(255,107,44,.35);transform:translateY(-1px);}
.login-link{text-align:center;font-size:.82rem;color:var(--text-sub);padding-top:14px;border-top:1px solid var(--border);}
.login-link a{color:var(--orange);font-weight:700;text-decoration:none;margin-left:4px;}
.login-link a:hover{text-decoration:underline;}
.theme-toggle{position:fixed;top:20px;right:20px;z-index:100;width:42px;height:42px;border-radius:50%;border:1.5px solid var(--border);background:var(--card);color:var(--text);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;transition:all .2s;}
.theme-toggle:hover{border-color:var(--orange);color:var(--orange);}
.req{color:var(--red);}
</style>
</head>
<body>
<div class="bg-shapes">
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
</div>

<button class="theme-toggle" onclick="toggleTheme()" id="themeBtn"><i class="fas fa-moon" id="themeIcon"></i></button>

<div class="card">
  <div class="logo">
    <div class="logo-icon"><i class="fas fa-car-side"></i></div>
    <div>
      <div class="logo-text">AGMS</div>
      <div class="logo-sub">Garage Management</div>
    </div>
  </div>

  <div class="title">Create Account</div>
  <div class="subtitle">Register to access the garage dashboard</div>

  <form method="POST" action="{{ route('register') }}">
    @csrf
    <div class="form-grid">
      <div class="form-group full">
        <label class="form-label">Full Name <span class="req">*</span></label>
        <input type="text" name="name" class="form-input" value="{{ old('name') }}" placeholder="John Kamau" required autofocus>
        @error('name')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
      </div>
      <div class="form-group full">
        <label class="form-label">Email Address <span class="req">*</span></label>
        <input type="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="john@garage.co.ke" required>
        @error('email')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Password <span class="req">*</span></label>
        <div class="input-wrap">
          <input type="password" name="password" id="pass1" class="form-input" placeholder="Min 8 chars" required>
          <button type="button" class="toggle-pass" onclick="togglePass('pass1','eye1')"><i class="fas fa-eye" id="eye1"></i></button>
        </div>
        @error('password')<div class="form-error"><i class="fas fa-circle-xmark"></i>{{ $message }}</div>@enderror
      </div>
      <div class="form-group">
        <label class="form-label">Confirm Password <span class="req">*</span></label>
        <div class="input-wrap">
          <input type="password" name="password_confirmation" id="pass2" class="form-input" placeholder="Repeat password" required>
          <button type="button" class="toggle-pass" onclick="togglePass('pass2','eye2')"><i class="fas fa-eye" id="eye2"></i></button>
        </div>
      </div>
    </div>

    <button type="submit" class="btn-register">
      <i class="fas fa-user-plus"></i> Create Account
    </button>

    <div class="login-link">
      Already have an account?
      <a href="{{ route('login') }}">Sign in here</a>
    </div>
  </form>
</div>

<script>
function togglePass(inputId, iconId) {
  const input = document.getElementById(inputId);
  const icon  = document.getElementById(iconId);
  input.type  = input.type === 'password' ? 'text' : 'password';
  icon.className = input.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
function toggleTheme() {
  const isDark = document.getElementById('htmlRoot').classList.toggle('dark');
  document.getElementById('themeIcon').className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  localStorage.setItem('agms-theme', isDark ? 'dark' : 'light');
}
(function(){
  if(localStorage.getItem('agms-theme')==='dark'){
    document.getElementById('htmlRoot').classList.add('dark');
    document.getElementById('themeIcon').className='fas fa-sun';
  }
})();
</script>
</body>
</html>