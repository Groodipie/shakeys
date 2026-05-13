<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rider Login — Shakey's</title>
<link rel="icon" type="image/png" href="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
:root{--sk-red:#C8181E;--sk-dark-red:#9B1015;}
body{margin:0;font-family:'Segoe UI',system-ui,sans-serif;background:#f5f5f5;min-height:100vh;display:flex;flex-direction:column;}
.logo-bar{background:#121212;padding:.85rem 1.5rem;display:flex;align-items:center;justify-content:center;}
.logo-bar img{height:60px;width:auto;display:block;}
.auth-bg{flex:1;background:#C8181E url('https://www.shakeyspizza.ph/images/bg-image.png') center top / 150% auto no-repeat;display:flex;align-items:flex-start;justify-content:center;padding:3rem 1rem;}
.auth-card{background:#fff;border-radius:4px;padding:48px 44px;max-width:440px;width:100%;box-shadow:0 12px 50px rgba(0,0,0,.22);}
.auth-card h4{font-weight:900;text-align:center;font-size:1.45rem;margin:0 0 .35rem;color:#1a1a1a;}
.auth-card .sub{text-align:center;color:#888;font-size:.85rem;margin-bottom:1.8rem;}
.field-wrap{margin-bottom:1.6rem;}
.field-wrap label{display:block;font-size:.76rem;font-weight:800;color:#444;margin-bottom:4px;letter-spacing:.3px;}
.field-wrap input{display:block;width:100%;border:none;border-bottom:1.5px solid #d0d0d0;outline:none;padding:.45rem 0;font-size:.9rem;background:transparent;color:#222;transition:border-color .18s;}
.field-wrap input:focus{border-bottom-color:var(--sk-red);}
.field-wrap.has-eye{position:relative;}
.field-wrap.has-eye input{padding-right:28px;}
.field-wrap .eye-btn{position:absolute;right:2px;bottom:10px;background:none;border:none;padding:0;cursor:pointer;color:#bbb;font-size:1rem;}
.btn-sk{display:block;width:100%;background:var(--sk-dark-red);color:#fff;border:none;border-radius:25px;font-weight:800;font-size:.95rem;padding:.75rem;cursor:pointer;transition:background .18s;letter-spacing:.3px;}
.btn-sk:hover{background:#7a0c10;}
</style>
</head>
<body>

<div class="logo-bar">
  <img src="https://www.shakeyspizza.ph/logos/Shakey_s%20USA%20LOGO.png" alt="Shakey's Pizza">
</div>

<div class="auth-bg">
  <div class="auth-card">
    <h4>Rider Login</h4>
    <p class="sub">Sign in to your rider account</p>

    <form method="POST" novalidate>
      <div class="field-wrap">
        <label>Rider ID</label>
        <input type="text" name="rider_id" placeholder="Enter your rider ID">
      </div>
      <div class="field-wrap has-eye">
        <label>Password</label>
        <input type="password" name="password" id="passInput" placeholder="Enter your password">
        <button type="button" class="eye-btn" onclick="togglePass()"><i class="bi bi-eye" id="eyeIcon"></i></button>
      </div>
      <button type="submit" class="btn-sk">Login</button>
    </form>
  </div>
</div>

<script>
function togglePass(){
  const inp=document.getElementById('passInput'),ico=document.getElementById('eyeIcon');
  if(inp.type==='password'){inp.type='text';ico.className='bi bi-eye-slash';}
  else{inp.type='password';ico.className='bi bi-eye';}
}
</script>
</body>
</html>
