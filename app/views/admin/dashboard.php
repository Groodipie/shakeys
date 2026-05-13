<style>
.dash-title{font-size:1.6rem;font-weight:800;color:#1a1a1a;margin:0 0 .35rem;}
.dash-sub{color:#888;font-size:.9rem;margin-bottom:2rem;}
.stat-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;margin-bottom:2rem;}
.stat-card{background:#fff;border-radius:8px;padding:1.25rem;box-shadow:0 2px 8px rgba(0,0,0,.05);border-left:4px solid var(--sk-red);}
.stat-card .label{font-size:.78rem;color:#888;text-transform:uppercase;font-weight:700;letter-spacing:.5px;margin-bottom:.4rem;}
.stat-card .value{font-size:1.8rem;font-weight:800;color:#1a1a1a;line-height:1;}
.panel{background:#fff;border-radius:8px;padding:1.5rem;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.panel h5{font-weight:800;font-size:1rem;color:#1a1a1a;margin:0 0 1rem;}
.panel p{color:#666;font-size:.9rem;margin:0;}
</style>

<h2 class="dash-title">Admin Dashboard</h2>
<p class="dash-sub">Welcome back. Here's a quick overview.</p>

<div class="stat-grid">
  <div class="stat-card">
    <div class="label">Orders Today</div>
    <div class="value">0</div>
  </div>
  <div class="stat-card">
    <div class="label">Active Riders</div>
    <div class="value">0</div>
  </div>
  <div class="stat-card">
    <div class="label">Customers</div>
    <div class="value">0</div>
  </div>
  <div class="stat-card">
    <div class="label">Revenue (Today)</div>
    <div class="value">&#8369;0</div>
  </div>
</div>

<div class="panel">
  <h5>Recent Activity</h5>
  <p>No activity yet. Hook up your data sources to populate this panel.</p>
</div>
