(() => {
const toast=document.getElementById('toast'),dialog=document.getElementById('dialog');let timer;
function notify(message){toast.textContent=message;toast.classList.add('show');clearTimeout(timer);timer=setTimeout(()=>toast.classList.remove('show'),2700)}
function explain(title,copy){document.getElementById('dialog-title').textContent=title;document.getElementById('dialog-copy').textContent=copy||'Ini simulasi UI. Belum terhubung ke backend Laravel atau database.';if(dialog.showModal)dialog.showModal();else notify(title+' — preview saja.')}
document.querySelectorAll('[data-view]').forEach(button=>button.addEventListener('click',()=>{const view=button.dataset.view;document.querySelectorAll('.nav').forEach(item=>item.classList.toggle('active',item.dataset.view===view));document.getElementById('crumb').textContent=view;document.getElementById('title').innerHTML=view==='Dashboard'?'Dashboard <span>Overview</span>':view;document.getElementById('subtitle').textContent=view==='Dashboard'?'Selamat datang kembali! Berikut ringkasan aktivitas perusahaan hari ini.':'Tampilan modul '+view+' dalam preview UI. Data yang tersedia hanya contoh.';document.getElementById('sidebar').classList.remove('open');if(view!=='Dashboard')notify('Modul '+view+' — navigasi demo, data tidak berubah.');window.scrollTo({top:0,behavior:'smooth'})}));
document.getElementById('menu').addEventListener('click',()=>document.getElementById('sidebar').classList.toggle('open'));
document.getElementById('add').addEventListener('click',()=>explain('Tambah Karyawan','Form ini belum terhubung ke Laravel. Tidak ada data yang disimpan pada preview statis.'));
document.getElementById('export').addEventListener('click',()=>explain('Export laporan','Ekspor file belum aktif. Fitur ekspor sungguhan membutuhkan backend.'));
document.getElementById('info').addEventListener('click',()=>explain('Tentang UI Preview','Halaman ini adalah preview statis GitHub Pages. Tidak ada login sungguhan, koneksi database, atau data perusahaan asli.'));
document.getElementById('notify').addEventListener('click',()=>explain('Notifikasi','Layanan notifikasi belum tersambung. Ini hanya simulasi interaksi UI.'));
document.getElementById('dismiss').addEventListener('click',()=>document.getElementById('notice').remove());
document.getElementById('range').addEventListener('change',e=>notify('Rentang grafik: '+e.target.value+' (data demo).'));
document.getElementById('search').addEventListener('keydown',e=>{if(e.key==='Enter'&&e.target.value.trim())notify('Pencarian “'+e.target.value.trim()+'” hanya simulasi.');});
document.querySelectorAll('.row-more').forEach(b=>b.addEventListener('click',()=>explain('Opsi karyawan','Menu tindakan belum aktif. Tidak ada data yang diubah atau dihapus.')));
})();