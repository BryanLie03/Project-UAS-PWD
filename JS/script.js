window.addEventListener("scroll", function () {
  /* Membaca input scroll */
  const navbar =
    document.querySelector(
      ".navbar",
    ); /* Deklarasi variabel navbar yang datanya diambil dari html */

  if (window.scrollY > 90) {
    /* jika scroll vertikal lebih dari 90 px maka menjalankan perintah dibawah */
    navbar.classList.add(
      "scrolled",
    ); /* Menambah class .scrolled pada .navbar */
  } else {
    navbar.classList.remove(
      "scrolled",
    ); /* Menghapus class .scrolled pada .navbar */
  }
});

const track = document.getElementById("slider-track");
const btnLeft = document.getElementById("btn-left");
const btnRight = document.getElementById("btn-right");

let spam = false; /* Memerintahkan untuk mengabaikan input spam */

if (btnRight) {
  btnRight.addEventListener("click", function () {
    if (!track || spam) return; /* Jika card habis atau ada spam maka langsung keluar dari fungsi tersebut */

    /* Menghitung lebar keseluruhan kartu dan menyimpanya pada variabel card */
    const card = track.querySelector(".box-card").offsetWidth;
    const scrollStep = card + 30; /* Memajukan lebar kartu keseluruhan yang muncul dilayar sebanyak 30px sesuai gap antar card */

    track.scrollBy({
      left: scrollStep /* Menggeser card ke kiri */,
      behavior: "smooth",
    });
  });
}

if (btnLeft) {
  btnLeft.addEventListener("click", function () {
    if (!track || spam) return;

    const card = track.querySelector(".box-card").offsetWidth;
    const scrollStep = card + 30;

    track.scrollBy({
      left: -scrollStep /* Menggeser card ke kanan */,
      behavior: "smooth",
    });
  });
}

const sejarahTrack = document.getElementById("sejarah-track");
const dots = document.querySelectorAll(".sejarah-dot .dot");
let autoScrollTimer; /* Menjalankan fungsi auto scroll */

function autoScrollSejarah() {
  if (!sejarahTrack) return;

  const timelineWidth =
    sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;
  const scrollStep = timelineWidth + 30;

  /* jika total scroll left lebih besar dari lebar track sejarah maka track akan kembali ke awal */
  if (
    sejarahTrack.scrollLeft + sejarahTrack.clientWidth >=
    sejarahTrack.scrollWidth - 10
  ) {
    sejarahTrack.scrollTo({
      left: 0,
      behavior: "smooth",
    });
  } else {
    sejarahTrack.scrollBy({
      left: scrollStep,
      behavior: "smooth",
    });
  }
}

function startAutoScroll() {
  autoScrollTimer = setInterval(
    autoScrollSejarah,
    10000,
  ); /* Interval 10 detik */
}

function resetAutoScroll() {
  /* Jika user mengganti halaman menggunakan dot maka timer interval direset ke 0 */
  clearInterval(autoScrollTimer);
  startAutoScroll();
}

startAutoScroll();

function updateDots() {
  if (!sejarahTrack) return;

  const timelineWidth =
    sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;

  /* Menghitung dengan pembulatan untuk menentukan indeks dot pada sejarah */
  const currentIndex = Math.round(
    sejarahTrack.scrollLeft / (timelineWidth + 30),
  );

  dots.forEach((dot) =>
    dot.classList.remove("active"),
  ); /* Memastikan semua dot statusnya tidak aktif */

  if (dots[currentIndex]) {
    dots[currentIndex].classList.add(
      "active",
    ); /* Menambah class active sesuai dengan index dari perhitungan sebelumnya */
  }
}

if (sejarahTrack) {
  sejarahTrack.addEventListener("scroll", updateDots);
}

dots.forEach((dot, index) => {
  dot.addEventListener("click", () => {
    if (!sejarahTrack) return;

    const cardWidth =
      sejarahTrack.querySelector(".sejarah-timeline").offsetWidth;
    const scrollStep = cardWidth + 30;

    sejarahTrack.scrollTo({
      left: index * scrollStep,
      behavior: "smooth",
    });

    resetAutoScroll();
  });
});

function muncul() {
  var elements = document.querySelectorAll(".animasi-muncul");
  for (var i = 0; i < elements.length; i++) {
    /* Fungsi Perulangan */
    var windowHeight =
      window.innerHeight; /* Menghitung tinggi layar pengguna */
    var elementTop =
      elements[i].getBoundingClientRect()
        .top; /* Mengecek elemen sudah muncul dilayar atau masih dibawah */
    var elementVisible = 150; /* Menentukan berapa jarak elemen dalam elemen yang masuk dalam layar sebelum muncul */

    if (elementTop < windowHeight - elementVisible) {
      elements[i].classList.add("active");
    }
  }
}

/* Memastikan fungsi muncul otomatis dijalankan setiap scroll */
window.addEventListener("scroll", muncul);
/* langsung memunculkan bagian atas saat reload sehingga tidak perlu scroll ke atas lagi untuk mentriger fungsi muncul */
muncul();

const formDoa = document.getElementById("doa-form");
const inputTanggal = document.getElementById("tanggal");
const inputDoa = document.getElementById("isi-doa");
const btnSubmitDoa = document.getElementById("btn-submit-doa");

if (formDoa) {
  const hariIni = new Date();

  // Format tanggal ke YYYY-MM-DD (format standar input date HTML)
  const formatTanggal = (date) => {
    const yyyy = date.getFullYear();
    const mm = String(date.getMonth() + 1).padStart(2, "0"); // Bulan dimulai dari 0
    const dd = String(date.getDate()).padStart(2, "0");
    return `${yyyy}-${mm}-${dd}`;
  };

  // Set tanggal minimal (Hari Ini)
  inputTanggal.min = formatTanggal(hariIni);

  // Hitung dan set tanggal maksimal (7 Hari ke Depan)
  const tujuhHariLalu = new Date();
  tujuhHariLalu.setDate(hariIni.getDate() + 7);
  inputTanggal.max = formatTanggal(tujuhHariLalu);

  function cekFormDoa() {
    if (inputTanggal.value !== "" && inputDoa.value.trim() !== "") {
      btnSubmitDoa.removeAttribute("disabled");
    } else {
      btnSubmitDoa.setAttribute("disabled", "true");
    }

    if (inputTanggal.value !== "") {
      inputTanggal.parentElement.classList.remove("invalid");
    }
    if (inputDoa.value.trim() !== "") {
      inputDoa.parentElement.classList.remove("invalid");
    }
  }

  inputTanggal.addEventListener("input", cekFormDoa);
  inputDoa.addEventListener("input", cekFormDoa);

  formDoa.addEventListener("submit", function (e) {
    let isValid = true;

    if (inputTanggal.value === "") {
      inputTanggal.parentElement.classList.add("invalid");
      isValid = false;
    }

    if (inputDoa.value.trim() === "") {
      inputDoa.parentElement.classList.add("invalid");
      isValid = false;
    }

    // Jika form TIDAK valid, cegah submit (e.preventDefault)
    if (!isValid) {
      e.preventDefault();
    } else {
      // Jika form valid, kita tampilkan tombol loading agar tidak di-klik 2 kali
      btnSubmitDoa.textContent = "Mengirim...";
      btnSubmitDoa.style.opacity = "0.7";
      // e.preventDefault() TIDAK DIPANGGIL di sini,
      // sehingga browser akan mengirim data secara normal ke sv_doa.php
    }
  });
}

// ====================================
// TOMBOL SCROLL KE ATAS
// ====================================
const btnScrollTop = document.getElementById("btn-scroll-top");

if (btnScrollTop) {
  // Fungsi 1: Mendeteksi scroll pengguna
  window.addEventListener("scroll", function () {
    // Jika pengguna scroll lebih dari 300px ke bawah, munculkan tombol
    if (window.scrollY > 300) {
      btnScrollTop.classList.add("muncul");
    } else {
      // Jika kembali ke atas (kurang dari 300px), sembunyikan tombol
      btnScrollTop.classList.remove("muncul");
    }
  });

  // Fungsi 2: Aksi saat tombol diklik
  btnScrollTop.addEventListener("click", function () {
    window.scrollTo({
      top: 0,
      behavior: "smooth"
    });
    // Anda tidak perlu menulis perintah untuk menyembunyikan tombol di sini,
    // karena saat layar kembali ke atas, event listener scroll (Fungsi 1) 
    // akan otomatis mendeteksi posisi 0 dan menyembunyikannya.
  });
}
