<?php
session_start();

if (!isset($_SESSION["loggedin"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LPG Booking Eligibility Tracker</title>
<style>
  :root{
    --blue:#1565c0; --green:#2e7d32; --red:#c62828; --gray:#666; --bg:#f4f6f8;
  }
  *{box-sizing:border-box;}
  body{font-family:Segoe UI,Arial,sans-serif;background:var(--bg);margin:0;padding:0;color:#222;}
  header{background:var(--blue);color:#fff;padding:14px 20px;}
  header h1{margin:0;font-size:20px;}
  header p{margin:4px 0 0;font-size:13px;opacity:.9;}
  .container{max-width:1200px;margin:0 auto;padding:16px;}
  .card{background:#fff;border-radius:8px;padding:16px;margin-bottom:16px;box-shadow:0 1px 3px rgba(0,0,0,.1);}
  .row{display:flex;gap:10px;flex-wrap:wrap;align-items:center;}
  input,select{padding:8px 10px;border:1px solid #ccc;border-radius:6px;font-size:14px;}
  input[type=text],input[type=password]{flex:1;min-width:140px;}
  button{padding:8px 14px;border:none;border-radius:6px;font-size:14px;cursor:pointer;color:#fff;}
  .btn-blue{background:var(--blue);}
  .btn-green{background:var(--green);}
  .btn-red{background:var(--red);}
  .btn-gray{background:var(--gray);}
  button:hover{opacity:.9;}
  table{width:100%;border-collapse:collapse;margin-top:10px;font-size:13px;}
  th,td{padding:8px 6px;border-bottom:1px solid #eee;text-align:left;white-space:nowrap;}
  th{background:#fafafa;position:sticky;top:0;}
  tr:hover{background:#f9fbff;}
  .tag{padding:3px 8px;border-radius:12px;font-size:12px;font-weight:600;color:#fff;}
  .tag-eligible{background:var(--green);}
  .tag-wait{background:#999;}
  .tag-booked{background:var(--blue);}
  .tabs{display:flex;gap:8px;margin-bottom:10px;}
  .tab{padding:6px 14px;border-radius:20px;background:#eee;cursor:pointer;font-size:13px;}
  .tab.active{background:var(--blue);color:#fff;}
  .small{font-size:12px;color:var(--gray);}
  .pw{font-family:monospace;}
  .table-wrap{max-height:65vh;overflow:auto;border:1px solid #eee;border-radius:6px;}
  .count-badge{background:#fff;color:var(--blue);border-radius:10px;padding:1px 8px;font-size:12px;margin-left:6px;}
  .actions button{padding:4px 8px;font-size:12px;margin-right:4px;}
  #toast{position:fixed;bottom:20px;right:20px;background:#333;color:#fff;padding:10px 16px;border-radius:6px;display:none;z-index:99;}
  .editing{background:#fff8e1 !important;}
</style>
</head>
<body>

<header>
  <h1>🔵 LPG Booking Eligibility Tracker</h1>
  <p>Track 600+ Indane customers — auto-shows who is eligible to book (45 days after delivery) design by A.Jana </p>

<a href="logout.php"
style="
float:right;
background:#dc3545;
color:white;
padding:8px 18px;
text-decoration:none;
border-radius:6px;
font-weight:bold;
margin-top:5px;
">
Logout
</a>
</header>

<div class="container">

  <div class="card">
    <h3 style="margin-top:0">➕ Add Customer</h3>
    <div class="row">
      <input type="text" id="f-name" placeholder="Consumer Name">
      <input type="text" id="f-consno" placeholder="Consumer No.">
      <input type="text" id="f-lpgid" placeholder="LPG Login ID">
      <input type="text" id="f-pw" placeholder="Password">
      <input type="date" id="f-delivery" title="Last Delivery Date">
      <button class="btn-blue" onclick="addCustomer()">Add Customer</button>
    </div>
    <p class="small">Leave "Last Delivery Date" blank if customer has never had a delivery yet (will show as Eligible) and for check booking history copy coustomer lpg ld.</p>
<a href="https://cx.indianoil.in/EPICIOCL/faces/GrievanceMainPage.jspx"
   target="_blank"
   style="
   background:#28a745;
   color:white;
   padding:10px 18px;
   text-decoration:none;
   border-radius:6px;
   font-weight:bold;
   margin-right:10px;">
   📋 Booking History
</a>
  </div>

  <div class="card">
    <div class="row" style="justify-content:space-between">
      <div class="tabs">
        <div class="tab active" data-f="all" onclick="setFilter('all')">All <span class="count-badge" id="c-all">0</span></div>
        <div class="tab" data-f="eligible" onclick="setFilter('eligible')">Eligible Today <span class="count-badge" id="c-eligible">0</span></div>
        <div class="tab" data-f="wait" onclick="setFilter('wait')">Waiting <span class="count-badge" id="c-wait">0</span></div>
        <div class="tab" data-f="booked" onclick="setFilter('booked')">Booked (pending delivery) <span class="count-badge" id="c-booked">0</span></div>
      </div>
      <input type="text" id="search" placeholder="🔍 Search name / consumer no / LPG ID" style="min-width:260px" oninput="render()">
    </div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>#</th><th>Name</th><th>Consumer No.</th><th>LPG ID</th><th>Password</th>
            <th>Last Delivery</th><th>Eligible From</th><th>Status</th><th>Actions</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </div>
  </div>

  <div class="card small">
    <b>How it works:</b>
    <ul>
      <li><b>Mark Booked</b> when you complete an online booking for that customer today.</li>
      <li><b>Mark Delivered</b> once the cylinder is actually delivered — this resets the 45-day countdown.</li>
      <li>A customer becomes <b>Eligible</b> again automatically 45 days after their last delivery date.</li>
      <li>All data is saved automatically and privately to your account — only you can see it.</li>
    </ul>
  </div>
</div>

<div id="toast"></div>

<script>
const ELIGIBILITY_DAYS = 45;
let customers = [];
let filter = 'all';

function todayStr(){
  return new Date().toISOString().slice(0,10);
}
function addDays(dateStr, days){
  const d = new Date(dateStr+'T00:00:00');
  d.setDate(d.getDate()+days);
  return d.toISOString().slice(0,10);
}
function daysBetween(a,b){
    return Math.round((new Date(b)-new Date(a))/86400000);
}

function showToast(msg){
  const t = document.getElementById('toast');
  t.textContent = msg;
  t.style.display = 'block';
  setTimeout(()=>t.style.display='none', 2000);
}

async function loadData(){

    const response = await fetch("api/get_customers.php");

    customers = await response.json();

    customers.forEach(c=>{

        if(c.lastDelivery==="0000-00-00")
            c.lastDelivery=null;

        if(c.lastBooking==="0000-00-00")
            c.lastBooking=null;

    });

    render();

}


function getStatus(c){
  const today = todayStr();
  // If booked after last delivery and no new delivery yet -> Booked (pending)
  if(c.lastBooking && (!c.lastDelivery || c.lastBooking >= c.lastDelivery)){
    return {status:'booked', label:'Booked – awaiting delivery'};
  }
  if(!c.lastDelivery){
    return {status:'eligible', label:'Eligible (never delivered)'};
  }
  const eligibleFrom = addDays(c.lastDelivery, ELIGIBILITY_DAYS);
  if(today >= eligibleFrom){
    return {status:'eligible', label:'Eligible Now'};
  }
  const daysLeft = daysBetween(today, eligibleFrom);
  return {status:'wait', label:`Wait ${daysLeft} day(s)`, eligibleFrom};
}

function setFilter(f){
  filter = f;
  document.querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
  document.querySelector(`.tab[data-f="${f}"]`).classList.add('active');
  render();
}

async function addCustomer(){
  const name = document.getElementById('f-name').value.trim();
  const consno = document.getElementById('f-consno').value.trim();
  const lpgid = document.getElementById('f-lpgid').value.trim();
  const pw = document.getElementById('f-pw').value.trim();
  const delivery = document.getElementById('f-delivery').value;

  if(!name || !consno){
    showToast('⚠️ Name and Consumer No. required');
    return;
  }
  await fetch("api/add_customer.php", {
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        name,
        consno,
        lpgid,
        pw,
        lastDelivery: delivery || null,
        lastBooking: null
    })
});

await loadData();

document.getElementById('f-name').value = '';
document.getElementById('f-consno').value = '';
document.getElementById('f-lpgid').value = '';
document.getElementById('f-pw').value = '';
document.getElementById('f-delivery').value = '';

render();
showToast('✅ Customer added');
}
async function markBooked(id){

    await fetch("api/mark_booked.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            id:id
        })
    });

    await loadData();

    showToast('📦 Marked booked today');
}

async function markDelivered(id){

    await fetch("api/mark_delivered.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            id:id
        })
    });

    await loadData();

    showToast('🚚 Marked delivered today');
}

async function deleteCustomer(id){

    if(!confirm("Delete this customer?")) return;

    await fetch("api/delete_customer.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            id:id
        })
    });

    await loadData();

    showToast("🗑 Customer deleted");
}

async function editCustomer(id){

    const c = customers.find(x=>x.id==id);

    if(!c) return;

    const name = prompt("Name", c.name);
    if(name===null) return;

    const consno = prompt("Consumer No", c.consno);
    if(consno===null) return;

    const lpgid = prompt("LPG ID", c.lpgid);
    if(lpgid===null) return;

    const pw = prompt("Password", c.pw);
    if(pw===null) return;

    const delivery = prompt(
        "Last Delivery (YYYY-MM-DD)",
        c.lastDelivery || ""
    );

    await fetch("api/update_customer.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/json"
        },
        body:JSON.stringify({
            id:id,
            name:name,
            consno:consno,
            lpgid:lpgid,
            pw:pw,
            lastDelivery:delivery,
            lastBooking:c.lastBooking
        })
    });

    await loadData();

    showToast("✅ Customer updated");

}

function render(){
  const tbody = document.getElementById('tbody');
  const search = document.getElementById('search').value.toLowerCase();

  let counts = {all:0, eligible:0, wait:0, booked:0};
  const rows = [];

  customers.forEach((c, idx)=>{
    const st = getStatus(c);
    counts.all++;
    counts[st.status]++;

    if(search){
      const hay = (c.name+' '+c.consno+' '+(c.lpgid||'')).toLowerCase();
      if(!hay.includes(search)) return;
    }
    if(filter!=='all' && filter!==st.status) return;

    const tagClass = st.status==='eligible'?'tag-eligible':st.status==='booked'?'tag-booked':'tag-wait';
    rows.push(`
      <tr>
        <td>${idx+1}</td>
        <td>${esc(c.name)}</td>
        <td>${esc(c.consno)}</td>
        <td>${esc(c.lpgid||'-')}</td>
        <td class="pw">${esc(c.pw||'-')}</td>
        <td>${c.lastDelivery||'-'}</td>
        <td>${st.eligibleFrom || (st.status==='eligible'?'—':'-')}</td>
        <td><span class="tag ${tagClass}">${st.label}</span></td>
        <td class="actions">
          ${st.status==='eligible' ? `<button class="btn-green" onclick="markBooked('${c.id}')">Book Today</button>` : ''}
          <button class="btn-blue" onclick="markDelivered('${c.id}')">Delivered Today</button>
          <button class="btn-gray" onclick="editCustomer('${c.id}')">Edit</button>
          <button class="btn-red" onclick="deleteCustomer('${c.id}')">Del</button>
        </td>
      </tr>
    `);
  });

  tbody.innerHTML = rows.join('') || `<tr><td colspan="9" style="text-align:center;padding:30px;color:#999">No customers found</td></tr>`;

  document.getElementById('c-all').textContent = counts.all;
  document.getElementById('c-eligible').textContent = counts.eligible;
  document.getElementById('c-wait').textContent = counts.wait;
  document.getElementById('c-booked').textContent = counts.booked;
}

function esc(s){
  if(!s) return '';
  return s.replace(/[&<>"']/g, m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
}

loadData();
</script>
</body>
</html>
