/* ====================================
   SIDEBAR TOGGLE
   ==================================== */
function toggleSidebar() {
  document.getElementById("sidebar").classList.toggle("collapsed");
  document.getElementById("mainContent").classList.toggle("expanded");
}

/* ====================================
   MODAL MANAGER
   ==================================== */
function bukaModalBasic(idModal) {
  const modal = document.getElementById(idModal);
  if (modal) modal.classList.add("show");
}

function tutupModalBasic(idModal) {
  const modal = document.getElementById(idModal);
  if (modal) {
    modal.classList.remove("show");
    // Reset form inside if exists
    const form = modal.querySelector("form");
    if (form) form.reset();
  }
}

/* Modal Konfirmasi Hapus/Aksi Biasa */
function bukaModalConfirm(urlAksi, pesan) {
  document.getElementById("modalMessage").textContent = pesan;
  document.getElementById("modalConfirmBtn").href = urlAksi;
  bukaModalBasic("customConfirmModal");
}

/* Modal Peringatan Hapus Event (Jika Ada Foto) */
function hapusEvent(id_event, nama_event, photo_count) {
  if (photo_count > 0) {
    document.getElementById("warningEventName").textContent = nama_event;
    document.getElementById("warningPhotoCount").textContent = photo_count;
    document.getElementById("btnForceDelete").href =
      `sv_dashboard.php?aksi=hapus_event_force&id=${id_event}`;
    bukaModalBasic("warningDeleteEventModal");
  } else {
    bukaModalConfirm(
      `sv_dashboard.php?aksi=hapus_event&id=${id_event}`,
      `Apakah Anda yakin ingin menghapus event ${nama_event}?`,
    );
  }
}

/* Modal Edit Data */
function bukaEditEvent(id, nama, tanggal, deskripsi) {
  document.getElementById("edit_id_event").value = id;
  document.getElementById("edit_nama_event").value = nama;
  document.getElementById("edit_tanggal_event").value = tanggal;
  document.getElementById("edit_deskripsi_event").value = deskripsi;
  bukaModalBasic("modalEditEvent");
}

function bukaEditGaleri(id_gallery, id_event) {
  document.getElementById("edit_id_gallery").value = id_gallery;
  document.getElementById("edit_event_galeri").value = id_event;
  bukaModalBasic("modalEditGaleri");
}

/* ====================================
   FITUR NAMBAH MASSAL GALERI
   ==================================== */
function cekLimitUpload(input) {
  const maxFiles = 4; // Batas maksimal foto
  const maxTotalSizeMB = 40;
  const maxTotalSizeBytes = maxTotalSizeMB * 1024 * 1024;

  // 1. Cek jumlah file (Client-side)
  if (input.files.length > maxFiles) {
    // Menampilkan pesan ke modal
    document.getElementById("infoModalMessage").innerHTML =
      "<strong style='color:red;'>Gagal!</strong><br>Anda tidak bisa mengupload lebih dari <strong>" +
      maxFiles +
      " foto</strong> sekaligus. Silakan kurangi jumlah foto Anda.";

    // Membuka modal agar muncul di paling depan
    bukaModalBasic("infoModal");

    // Mereset input file agar file yang dipilih tadi tidak terkirim
    input.value = "";
    return;
  }

  // 2. Cek total ukuran file
  let totalSize = 0;
  for (let i = 0; i < input.files.length; i++) {
    totalSize += input.files[i].size;
  }

  if (totalSize > maxTotalSizeBytes) {
    document.getElementById("infoModalMessage").innerHTML =
      "<strong style='color:red;'>Gagal!</strong><br>Total ukuran foto (" +
      (totalSize / 1024 / 1024).toFixed(2) +
      " MB) melebihi batas " +
      maxTotalSizeMB +
      " MB.";

    bukaModalBasic("infoModal");
    input.value = "";
  }
}

/* ====================================
   FITUR HAPUS MASSAL GALERI
   ==================================== */
function toggleCheckAll(source) {
  const checkboxes = document.querySelectorAll(
    'input[name="id_galeri_hapus[]"]',
  );
  checkboxes.forEach((cb) => (cb.checked = source.checked));
}

function aktifkanModeHapus() {
  // 1. Tampilkan checkbox select all di header
  document.getElementById("chkSemua").style.display = "inline-block";

  // 2. Tampilkan semua checkbox item galeri yang tersembunyi
  let checkboxes = document.querySelectorAll(".chk-foto-galeri");
  checkboxes.forEach(function (chk) {
    chk.style.display = "inline-block";
  });

  // 3. Sembunyikan tombol "Mode Hapus", tampilkan tombol "Eksekusi" dan "Batal"
  document.getElementById("btnModeHapus").style.display = "none";
  document.getElementById("btnEksekusiHapus").style.display = "inline-block";
  document.getElementById("btnBatalHapus").style.display = "inline-block"; // MENAMPILKAN TOMBOL BATAL
}

// FUNGSI BARU UNTUK MEMBATALKAN MODE HAPUS
function batalkanModeHapus() {
  // 1. Sembunyikan & hilangkan centang pada checkbox 'Pilih Semua'
  let chkSemua = document.getElementById("chkSemua");
  chkSemua.style.display = "none";
  chkSemua.checked = false;

  // 2. Sembunyikan & hilangkan centang pada semua checkbox galeri
  let checkboxes = document.querySelectorAll(".chk-foto-galeri");
  checkboxes.forEach(function (chk) {
    chk.style.display = "none";
    chk.checked = false; // Membersihkan centang sebelumnya
  });

  // 3. Tampilkan kembali tombol "Mode Hapus", sembunyikan tombol "Eksekusi" dan "Batal"
  document.getElementById("btnModeHapus").style.display = "inline-block";
  document.getElementById("btnEksekusiHapus").style.display = "none";
  document.getElementById("btnBatalHapus").style.display = "none";
}

function prosesHapusMassal() {
  // Ambil semua checkbox yang dicentang
  const checked = document.querySelectorAll(
    'input[name="id_galeri_hapus[]"]:checked',
  );

  if (checked.length === 0) {
    document.getElementById("infoModalMessage").textContent =
      "Pilih setidaknya 1 foto untuk dihapus terlebih dahulu!";
    bukaModalBasic("infoModal");
    return;
  }

  // Ambil jumlah foto yang dipilih
  const jumlahFoto = checked.length;

  // Ganti teks di modal secara dinamis
  document.getElementById("pesanHapusMassal").innerHTML =
    "Anda yakin ingin menghapus <strong>" +
    jumlahFoto +
    "</strong> foto yang telah dicentang secara permanen?";

  // Buka modal
  bukaModalBasic("confirmHapusMassalModal");
}

function submitHapusMassal() {
  document.getElementById("formGaleriMassal").submit();
}

/* ====================================
   PEMBERSIH NOTIFIKASI OTOMATIS (FLOATING)
   ==================================== */
document.addEventListener("DOMContentLoaded", function () {
  const alerts = document.querySelectorAll(".alert-floating");
  if (alerts.length > 0) {
    setTimeout(() => {
      alerts.forEach((alert) => {
        alert.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        alert.style.opacity = "0";
        alert.style.transform = "translateX(100%)";
        setTimeout(() => (alert.style.display = "none"), 500);
      });
      const url = new URL(window.location);
      if (url.searchParams.has("msg") || url.searchParams.has("aksi")) {
        url.searchParams.delete("msg");
        url.searchParams.delete("aksi");
        window.history.replaceState(null, null, url);
      }
    }, 4000);
  }
});

/* ====================================
   AUTO-SAVE CHECKBOX EVENT
   ==================================== */
document.addEventListener("DOMContentLoaded", function () {
  const checkboxesEvent = document.querySelectorAll(".check-tampil-event");
  if (checkboxesEvent.length > 0) {
    checkboxesEvent.forEach((box) => {
      box.addEventListener("change", function () {
        const eventId = this.getAttribute("data-id");
        const isChecked = this.checked ? 1 : 0;
        const currentBox = this;

        fetch(`sv_dashboard.php?aksi=toggle_tampil_event`, {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `id_event=${eventId}&is_checked=${isChecked}`,
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.status === "error") {
              currentBox.checked = false;
              document.getElementById("infoModalMessage").textContent =
                data.msg;
              bukaModalBasic("infoModal");
            }
          })
          .catch(() => {
            currentBox.checked = !isChecked;
            alert("Koneksi bermasalah.");
          });
      });
    });
  }
});
