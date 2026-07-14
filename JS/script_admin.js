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
   FITUR HAPUS MASSAL GALERI
   ==================================== */
function toggleCheckAll(source) {
  const checkboxes = document.querySelectorAll(
    'input[name="id_galeri_hapus[]"]',
  );
  checkboxes.forEach((cb) => (cb.checked = source.checked));
}

function prosesHapusMassal() {
  const checked = document.querySelectorAll(
    'input[name="id_galeri_hapus[]"]:checked',
  );
  if (checked.length === 0) {
    alert("Pilih setidaknya 1 foto untuk dihapus!");
    return;
  }
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
