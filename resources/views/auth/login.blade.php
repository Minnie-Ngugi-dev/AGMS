<!DOCTYPE html>
<html lang="en" id="htmlRoot">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — AGMS</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
:root {
  --orange:#FF6B2C; --orange-l:#FFF0E9;
  --navy:#0F2040;   --navy-m:#1A3260;
  --green:#16A34A;  --green-l:#DCFCE7;
  --red:#DC2626;    --red-l:#FEE2E2;
  --font-h:'Space Grotesk',sans-serif;
  --font-b:'DM Sans',sans-serif;
  --font-m:'DM Mono',monospace;
  --r:12px; --r-sm:8px;
  /* Light mode */
  --bg:#F8FAFC;
  --card:#ffffff;
  --border:#E2E8F0;
  --text:#0F172A;
  --text-sub:#64748B;
  --input-bg:#F8FAFC;
  --shadow:0 20px 60px rgba(0,0,0,.08);
}
html.dark {
  --bg:#0A0F1E;
  --card:#111827;
  --border:#1F2937;
  --text:#F1F5F9;
  --text-sub:#94A3B8;
  --input-bg:#1F2937;
  --shadow:0 20px 60px rgba(0,0,0,.4);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:var(--font-b);background:var(--bg);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;transition:background .3s,color .3s;position:relative;overflow:hidden;}

/* Animated background */
.bg-shapes{position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0;}
.shape{position:absolute;border-radius:50%;filter:blur(80px);opacity:.35;animation:float 8s ease-in-out infinite;}
.shape-1{width:400px;height:400px;background:var(--orange);top:-100px;left:-100px;animation-delay:0s;}
.shape-2{width:300px;height:300px;background:var(--navy);bottom:-80px;right:-80px;animation-delay:3s;}
.shape-3{width:200px;height:200px;background:#7C3AED;top:50%;right:10%;animation-delay:1.5s;}
html.dark .shape{opacity:.15;}
@keyframes float{0%,100%{transform:translateY(0) scale(1);}50%{transform:translateY(-30px) scale(1.05);}}

/* Card */
.login-card{background:var(--card);border:1px solid var(--border);border-radius:20px;box-shadow:var(--shadow);width:100%;max-width:420px;padding:40px;position:relative;z-index:1;animation:slideUp .5s ease both;}
@keyframes slideUp{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);}}

/* Logo */
.logo{display:flex;align-items:center;gap:12px;margin-bottom:32px;}
.logo-icon{width:48px;height:48px;background:var(--orange);border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;box-shadow:0 8px 24px rgba(255,107,44,.35);}
.logo-text{font-family:var(--font-h);font-size:1.3rem;font-weight:700;color:var(--text);}
.logo-sub{font-size:.68rem;color:var(--text-sub);letter-spacing:1.5px;text-transform:uppercase;}

/* Heading */
.login-title{font-family:var(--font-h);font-size:1.5rem;font-weight:700;color:var(--text);margin-bottom:6px;}
.login-sub{font-size:.83rem;color:var(--text-sub);margin-bottom:28px;}

/* Form */
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:.78rem;font-weight:600;color:var(--text);margin-bottom:6px;}
.form-input{width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:var(--r-sm);font-size:.88rem;font-family:var(--font-b);color:var(--text);background:var(--input-bg);transition:all .2s;outline:none;}
.form-input:focus{border-color:var(--orange);box-shadow:0 0 0 3px rgba(255,107,44,.12);background:var(--card);}
.input-wrap{position:relative;}
.input-wrap .form-input{padding-right:42px;}
.input-wrap .toggle-pass{position:absolute;right:13px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--text-sub);font-size:.85rem;padding:4px;transition:color .2s;}
.input-wrap .toggle-pass:hover{color:var(--orange);}

/* Remember + forgot */
.form-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.remember{display:flex;align-items:center;gap:7px;font-size:.8rem;color:var(--text-sub);cursor:pointer;}
.remember input{accent-color:var(--orange);}
.forgot{font-size:.8rem;color:var(--orange);text-decoration:none;font-weight:600;}
.forgot:hover{text-decoration:underline;}

/* Button */
.btn-login{width:100%;padding:12px;background:var(--orange);color:#fff;border:none;border-radius:var(--r-sm);font-size:.9rem;font-weight:700;font-family:var(--font-h);cursor:pointer;transition:all .2s;display:flex;align-items:center;justify-content:center;gap:8px;margin-bottom:20px;}
.btn-login:hover{background:#E55A1E;box-shadow:0 8px 24px rgba(255,107,44,.35);transform:translateY(-1px);}
.btn-login:active{transform:translateY(0);}

/* Register link */
.register-link{text-align:center;font-size:.82rem;color:var(--text-sub);padding-top:16px;border-top:1px solid var(--border);}
.register-link a{color:var(--orange);font-weight:700;text-decoration:none;margin-left:4px;}
.register-link a:hover{text-decoration:underline;}

/* Error */
.error-box{background:var(--red-l);border:1px solid #fca5a5;border-radius:var(--r-sm);padding:11px 14px;margin-bottom:16px;font-size:.82rem;color:var(--red);display:flex;align-items:center;gap:8px;}

/* Dark mode toggle */
.theme-toggle{position:fixed;top:20px;right:20px;z-index:100;width:42px;height:42px;border-radius:50%;border:1.5px solid var(--border);background:var(--card);color:var(--text);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:.9rem;transition:all .2s;box-shadow:0 2px 8px rgba(0,0,0,.1);}
.theme-toggle:hover{border-color:var(--orange);color:var(--orange);}
</style>
</head>
<body>

<div class="bg-shapes">
  <div class="shape shape-1"></div>
  <div class="shape shape-2"></div>
  <div class="shape shape-3"></div>
</div>

<button class="theme-toggle" onclick="toggleTheme()" title="Toggle dark mode" id="themeBtn">
  <i class="fas fa-moon" id="themeIcon"></i>
</button>

<div class="login-card">
  <div class="logo">
    <div class="logo-icon"><i class="fas fa-car-side"></i></div>
    <div>
      <div class="logo-text">AGMS</div>
      <div class="logo-sub">Garage Management</div>
    </div>
  </div>

  <div class="login-title">Welcome back 👋</div>
  <div class="login-sub">Sign in to your garage dashboard</div>

  @if($errors->any())
  <div class="error-box">
    <i class="fas fa-circle-xmark"></i>
    <div>
      @foreach($errors->all() as $error)
        <div>{{ $error }}</div>
      @endforeach
    </div>
  </div>
  @endif

  @if(session('status'))
  <div style="background:var(--green-l);border:1px solid #86efac;border-radius:var(--r-sm);padding:11px 14px;margin-bottom:16px;font-size:.82rem;color:var(--green);display:flex;align-items:center;gap:8px;">
    <i class="fas fa-check-circle"></i> {{ session('status') }}
  </div>
  @endif

  <form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
      <label class="form-label" for="email">Email Address</label>
      <div class="input-wrap">
        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}" placeholder="admin@garage.co.ke" required autofocus autocomplete="email">
      </div>
    </div>

    <div class="form-group">
      <label class="form-label" for="password">Password</label>
      <div class="input-wrap">
        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required autocomplete="current-password">
        <button type="button" class="toggle-pass" onclick="togglePassword()" id="eyeBtn" title="Show/hide password">
          <i class="fas fa-eye" id="eyeIcon"></i>
        </button>
      </div>
    </div>

    <div class="form-row">
      <label class="remember">
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
        Remember me
      </label>
      @if(Route::has('password.request'))
      <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
      @endif
    </div>

    <button type="submit" class="btn-login">
      <i class="fas fa-right-to-bracket"></i> Sign In
    </button>

    <div class="register-link">
      Don't have an account?
      <a href="{{ route('register') }}">Create one here</a>
    </div>
  </form>
</div>

<script>
// Password show/hide
function togglePassword() {
  const input = document.getElementById('password');
  const icon  = document.getElementById('eyeIcon');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye','fa-eye-slash');
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash','fa-eye');
  }
}

// Dark mode
function toggleTheme() {
  const html = document.getElementById('htmlRoot');
  const icon = document.getElementById('themeIcon');
  const isDark = html.classList.toggle('dark');
  icon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
  localStorage.setItem('agms-theme', isDark ? 'dark' : 'light');
}

// Persist theme
(function(){
  const saved = localStorage.getItem('agms-theme');
  if (saved === 'dark') {
    document.getElementById('htmlRoot').classList.add('dark');
    document.getElementById('themeIcon').className = 'fas fa-sun';
  }
})();
</script>
</body>
</html>