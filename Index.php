<?php
include "header.php";

$data_video_cms = null;

$query = mysqli_query($conn, "SELECT Title, link FROM youtube ORDER BY id_youtube DESC LIMIT 1");

if ($query && mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    
    $data_video_cms = [
        'judul'    => $row['Title'],
        'id_video' => $row['link']
    ];
}
?>

<main>
  <section class="hero">
    <div class="hero-overlay">
      <h1>BERTUMBUH<br />BERAKAR<br />BERBUAH<br />DI DALAM KRISTUS</h1>
      <p>
        Tempat bertumbuh dalam Kristus, mengalami kasih Tuhan, dan berjalan
        bersama dalam kebenaran
      </p>
      <div class="animasi-muncul">
        <a href="#jadwal-ibadah" class="btn-hero">Lihat Jadwal Ibadah</a>
      </div>
    </div>
  </section>

  <section class="welcome-section">
    <div class="container">
      <h2 class="animasi-muncul">Selamat datang di Gereja Yesus Sejati</h2>
      <div class="welcome-flex">
        <div class="blue-frame animasi-muncul">
          <figure class="welcome-image">
            <img src="Assets/img/jemaat1.jpeg" alt="Foto Jemaat" />
            <figcaption class="sr-only">Foto Jemaat Gereja Yesus Sejati Pontianak sedang beribadah</figcaption>
          </figure>
        </div>
        <div class="text-wrapper animasi-muncul">
          <p><strong>Salam damai sejahtera bagi kita semua.</strong></p>
          <br>
          <p>
            Selamat datang di Gereja Yesus Sejati Pontianak. Kami bersyukur
            dan berbahagia atas kunjungan Anda di situs ini. Kehadiran Anda,
            baik sebagai jemaat maupun sebagai simpatisan, adalah suatu
            berkat yang kami hargai dengan penuh sukacita.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="kepercayaan" id="dasar-kepercayaan">
    <div class="card-container">
      <h2 class="animasi-muncul">Dasar Kepercayaan</h2> 
      
      <div class="slider-wrapper animasi-muncul">
        <button id="btn-left" class="slider-btn" aria-label="Geser Kiri"> 
          <i class="fa-solid fa-chevron-left"></i>
        </button>

        <div class="card-grid" id="slider-track">
          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-1-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa Yesus adalah Firman yang menjadi manusia, Ia berkorban mati di atas kayu salib demi menyelamatkan umat manusia yang berdosa, pada hari ketiga bangkit kembali dan naik ke Surga. Dia adalah Juru selamat Tunggal manusia, Tuhan semesta alam dan Allah Yang Maha Esa.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:cross"></iconify-icon>
          </div>
    
          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-2-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa Kitab Suci Perjanjian Lama dan Perjanjian Baru yang diilhamkan oleh Allah adalah sumber tunggal kebenaran dan kehidupan beriman.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:book-bible"></iconify-icon>
          </div>

          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-3-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa Gereja Yesus Sejati didirikan oleh Roh Kudus pada masa hujan akhir, untuk memulihkan kembali gereja benar di jaman para rasul.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:church"></iconify-icon>
          </div>
    
          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-4-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa Baptisan Air adalah sakramen untuk penghapusan dosa dan kelahiran kembali, dilaksanakan dalam Nama Tuhan Yesus di air yang hidup dengan kepala menunduk dan segenap tubuh diselamkan ke dalam air. Pembaptis haruslah orang yang telah menerima Baptisan Air dan Baptisan Roh Kudus.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:droplet"></iconify-icon>
          </div>

          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-5-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa menerima Roh Kudus adalah jaminan bagian warisan Kerajaan Allah, dengan berbahasa roh sebagai bukti nyata penerimaan Roh Kudus.
            </p>
            <iconify-icon class="icon2" icon="fa7-solid:dove" width="50"></iconify-icon>
          </div>

          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-6-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa Sakramen Basuh kaki adalah untuk beroleh bagian dalam Tuhan, mengandung pengajaran saling mengasihi, menyucikan diri, merendahkan diri, melayani dan saling mengampuni; setiap orang yang telah dibaptis harus menerima Sakramen Basuh Kaki ini satu kali yang dilakukan dalam nama Yesus Kristus. Saling membasuh kaki dapat pula dilaksanakan apabila perlu.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:people-group"></iconify-icon>
          </div>

          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-7-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa Sakramen Perjamuan Kudus adalah untuk memperingati kematian Tuhan, bersama sama menerima daging dan darah Tuhan, menjadi satu dengan Tuhan untuk memperoleh hidup kekal dan kebangkitan kembali pada akhir jaman; Sakramen ini harus sering diadakan, penyelenggaraannya harus dilakukan dengan menggunakan satu ketul roti tidak beragi dan air buah anggur.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:cross"></iconify-icon>
          </div>

          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-8-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa hari Sabat (hari Sabtu) adalah hari kudus yang diberkati Allah, yang dipegang di bawah anugerah untuk memperingati penciptaan dan penyelamatan Allah, dengan menaruh pengharapan akan Sabat kekal dalam hidup yang akan datang.
            </p>
            <iconify-icon class="icon2" icon="fa6-regular:calendar-days"></iconify-icon>
          </div>

          <div class="box-card">
            <iconify-icon class="icon1" icon="iconamoon:number-9-circle-fill"></iconify-icon>
            <p class="card-text">
              Percaya bahwa manusia diselamatkan adalah karena kasih karunia dan juga oleh iman, manusia harus mengejar kesucian dengan bersandarkan Roh Kudus, mengamalkan pengajaran Alkitab, mengasihi Allah dan sesama manusia.
            </p>
            <iconify-icon class="icon2" icon="fa6-solid:heart"></iconify-icon>
          </div>

          <div class="box-card">
            <div class="icon-10">10</div>
            <p class="card-text">
              Percaya bahwa Tuhan Yesus akan turun dari Surga pada akhir jaman untuk menghakimi umat manusia, orang benar akan memperoleh hidup kekal, orang jahat akan memperoleh hukuman abadi.
            </p>
            <iconify-icon class="icon2" icon="fa6-regular:hourglass-half"></iconify-icon>
          </div>
        </div>

        <button id="btn-right" class="slider-btn" aria-label="Geser Kanan"> 
          <i class="fa-solid fa-chevron-right"></i>
        </button>
      </div> 
    </div> 
  </section>

  <section class="sejarah" id="sejarah">
    <div class="container">         
      <h2 class="animasi-muncul">Sejarah GYS Pontianak</h2>

      <div class="sejarah-slider animasi-muncul" id="sejarah-track">
        <div class="sejarah-timeline">
          <img src="Assets/img/Foto Jemaat 1.jpeg" alt="Sejarah 1949-1950" class="sejarah-bg"/>
          <div class="sejarah-overlay"></div>
          <div class="sejarah-text">
            <h3>Tahun 1949</h3>
            <p>
              Ajaran Gereja Yesus Sejati mulai dikenalkan pertama kali di Pontianak. Firman Allah tentang keselamatan itu diinjili oleh Pendeta wanita Maria Chen Suk Kwan (alm) langsung dari foochow, ibukota provinsi Fuchien, Tiongkok. 
              Pdt. Maria memiliki seorang anak angkat yang berasal dari Pontianak, yang bernama Paulus Lay Han Soen, dibaptis di Foochow dan keluarganya sudah menjadi anggota Gereja kemudian pindah dan menetap di pontianak saat itu.
            </p>

            <h3>Tahun 1950</h3>
            <p>
              Pada tahun ini mulai ada kebaktian, namun belum ada bangunan gereja pada masa itu, jadi ibadahnya dilaksanakan di ruangan kelas di sekolah wanita (ruangannya dipinjam). Kini bangunan tersebut telah diubah menjadi komplek pusat perbelanjaan Kapuas Indah. 
              Pada tahun itu ada seorang bapak yang bernama Lay Han Soen (alm) menyumbangkan sebidang tanah untuk gereja di Jl. Diponegoro Gg. II No. 34 Pontianak. 
            </p>
          </div>
        </div>

        <div class="sejarah-timeline">
          <img src="Assets/img/Foto Jemaat 2.jpeg" alt="Sejarah 1952-1954" class="sejarah-bg"/>
          <div class="sejarah-overlay"></div>
          <div class="sejarah-text">
            <h3>Tahun 1951</h3>
            <p>
              Pontianak telah melapor ke jakarta bahwa di Pontianak sudah ada jemaat dan mulai ada kebaktian dengan sejumlah simpatisan. Pada waktu itu juga dana mulai terkumpul hingga gereja mulai dibangun.
            </p>

            <h3>Tahun 1952</h3>
            <p>
              Gereja telah berhasil didirikan dan sekaligus pada tahun itulah ditetapkan sebagai tahun pendirian Gereja Yesus Sejati Cabang Pontianak. pada saat itu yang ikut kebaktian sebagian besar masih simpatisan.
            </p>

            <h3>Tahun 1954</h3>
            <p>
              pada 1954 adalah pembaptisan pertama pertama di Gereja Yesus Sejati Cabang Pontianak. Itulah alasan pada 1952 sebagian besar masih banyak simpatisan, dan pada tahun inilah Gereja ditahbiskan oleh Pdt. wanita Lim Yulia (alm), (nenek dari Pdt. Andrea Halingkar)
            </p>
          </div>
        </div>

        <div class="sejarah-timeline">
          <img src="Assets/img/Foto Jemaat 3.jpeg" alt="Sejarah 1954-1968" class="sejarah-bg"/>
          <div class="sejarah-overlay"></div>
          <div class="sejarah-text">
            <h3>Tahun 1954</h3>
            <p>
              Gereja Yesus Sejati pusat Indonesia mengutus Pdt. Siaw Stephen (alm), ayah dari Diaken Siaw Bin Yuen (alm) melakukan pembaptisan yang pertama kepada 8 orang (2 pria 6 wanita) 
            </p>

            <h3>Tahun 1956</h3>
            <p>
              Babtisan yang kedua ada di tahun 1956, dipimpin oleh Pdt. Kwok Ce Yen (alm). Beliau sering diutus oleh Gereja Pusat ke Gereja Pontianak. Pdt. Kwok Ce Yen meninggal pada tahun 1964.
            </p>

            <h3>Tahun 1968</h3>
            <p>
              Pada bulan April  Gereja Pontianak pertama kali dikunjungi oleh Pdt. dari Taiwan yaitu Pdt. John (yang akhirnya diangkat sebagai Penatua). pada awal Tahun 70an seorang Pdt. wanita Ho yang berasal dari Malaysia juga sempat bertugas di Pontianak.
            </p>
          </div>
        </div>

        <div class="sejarah-timeline">
          <img src="Assets/img/Foto Jemaat 4.jpeg" alt="Sejarah 1975" class="sejarah-bg"/>
          <div class="sejarah-overlay"></div>
          <div class="sejarah-text">
            <h3>Tahun 1975</h3>
            <p>
              setelah kurun waktu sepanjang 20 tahun perkembangan jemaat cukup pesat, gedung gereja tidak mempu menampung lagi. pada 25 Februari 1975, dengan bantuan dari dana gereja pusat, ketua majelis pusat Diaken Yusuf Subiantoro melalui keputusan majelis pusat menetapkan membeli sebidang tanah termasuk bangunan lama yang terletak di Jl. Haji Abbas I No. 27 Pontianak (bangunan Gereja sekarang).
            </p>

            <h3>Gedung Baru</h3>
            <p>
              Pada saat itu administrasi gereja dan kebaktian dipindahkan ke alamat baru dan 20 tahun kemudian ditetapkan untuk membangun membangun alamat baru. pemancangan tiang pertama gedung baru di Jl. Haji Abbas I No. 27 di pimpin oleh Pdt. Yuelrianto Setra (alm) pada Juni 1997. pentahbisan gedung gereja oleh Walikota Pontianak RA. Siregar pada hari sabtu tanggal 18 Juli 1998.
            </p>
          </div>
        </div>

        <div class="sejarah-timeline">
          <img src="Assets/img/Foto Jemaat 5.jpeg" alt="Sejarah 1949-1950" class="sejarah-bg"/>
          <div class="sejarah-overlay"></div>
          <div class="sejarah-text">
            <h3>Tahun 1997</h3>
            <p>
              karena letak geografis Pontianak Kalbar yang bertetangga dengan negeri jiran, dan adanya jalan internasional menghubungi Kuching Sarawak Malaysia Timur. ada informasi bahwa di kota tersebut ada jemaat. maka pada tahun 1997 Drs. Hendry Jurnawan, SH, MM seorang jemaat dari gereja Pontianak mencari informasi dan berhasil menemukan saudara seiman disana. sejak saat itu gereja Pontianak mendapat tugas pelayanan tetap ke Kuching.
            </p>

            <h3>Tahun 1999</h3>
            <p>
              Melihat perkembangan gereja dan program kerja, maka pada tahun 1999 didirikan sebuah yayasan kanaan yang diketuai oleh Sdr. Hendra Christin, mengelola lembaga pendidikan. kemudian di tahun 2000 didirikan TK (Taman Kanak-Kanak) tahun ajaran 2000/2001 dan tahun 2002 mendirikan SD (Sekolah Dasar) Kanaan. Dan Gereja Yesus Sejati Pontianak tetap berdiri hingga saat ini. Jika dihitung mulai dari tahun ini, maka umur Gereja Yesus Sejati Pontianak telah mencapai 74 tahun.
            </p>
          </div>
        </div>
      </div> 

      <div class="sejarah-dot"> 
        <div class="dot active"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
      </div>
    </div> 
  </section>

  <section class="kegiatan" id="kegiatan">
    <div class="kegiatan-container">
      <h2 class="animasi-muncul">Kegiatan-Kegiatan di Gereja Yesus Sejati</h2>

      <?php
      $query_kegiatan = mysqli_query($conn, "SELECT * FROM events WHERE `show` = 1 ORDER BY date DESC LIMIT 5");
      $no = 0; 

      if ($query_kegiatan && mysqli_num_rows($query_kegiatan) > 0) :
          while ($row = mysqli_fetch_assoc($query_kegiatan)) :
              
              $class_reverse = ($no % 2 != 0) ? ' reverse' : '';
      ?>
      
            <div class="image-kegiatan<?= $class_reverse ?> animasi-muncul">
              
              <?php 
                $gambar_path = !empty($row['image']) ? "uploads/event/" . htmlspecialchars($row['image']) : "Assets/img/KPI.jpeg"; 
              ?>
              <img src="<?= $gambar_path ?>" alt="<?= htmlspecialchars($row['event']) ?>">  
              
              <div class="text-kegiatan">
                <h3><?= htmlspecialchars($row['event']) ?></h3>
                <p>
                  <?= nl2br(htmlspecialchars($row['description'])) ?>
                </p>
              </div>
            </div>

      <?php
              $no++; 
          endwhile;
      else :
      ?>
          <div style="text-align: center; padding: 40px 0;">
            <p style="color: #64748b; font-style: italic;">Belum ada jadwal kegiatan yang ditambahkan saat ini.</p>
          </div>
      <?php endif; ?>

    </div>
  </section>

  <section class="youtube">
    <div class="youtube-container">
      <div class="youtube-text">
        <h4>Saksikan dan Dengarkan</h4>
        
        <h2><?= ($data_video_cms && !empty($data_video_cms['judul'])) ? htmlspecialchars($data_video_cms['judul']) : 'Livestream Khotbah' ?></h2>
        
        <p>Mari bertumbuh didalam Kristus melalui channel youtube Gereja Yesus Sejati</p>
        <a href="https://www.youtube.com/@GYSPontianak" target="_blank" class="btn-klik">Klik di sini</a>
      </div>

      <div class="video-youtube">
        <?php if ($data_video_cms && !empty($data_video_cms['id_video'])) : ?>
          <iframe 
            src="https://www.youtube.com/embed/<?= htmlspecialchars($data_video_cms['id_video']) ?>" 
            title="YouTube video player" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen> 
          </iframe>
        <?php else : ?>
          <div style="background: #0f172a; width: 100%; height: 100%; min-height: 250px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
            <p style="color: #cbd5e1; font-style: italic;">Video belum diatur oleh Administrator.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="jadwal" id="jadwal-ibadah">
    <div class="jadwal-container">
      <h2 class="animasi-muncul">Jadwal Ibadah</h2>

      <div class="jadwal-flex">
        <div class="animasi-muncul">
          <div class="jadwal-box">
            <h3>IBADAH SABAT</h3>
            <div class="jadwal-ibadah">
              <div class="jadwal-hari">Jumat</div>
              <div class="jadwal-waktu">18.30 <br> WIB</div>
            </div>
          </div>
        </div>

        <div class="animasi-muncul">
          <div class="jadwal-box">
            <h3>IBADAH SABAT</h3>
            <div class="jadwal-ibadah">
              <div class="jadwal-hari">Sabtu</div>
              <div class="jadwal-waktu kecil">10.00 (Pagi) WIB  <br> & <br> 14.00 (Siang) WIB</div>
            </div>
          </div>
        </div>

        <div class="animasi-muncul">
          <div class="jadwal-box">
            <h3>IBADAH PEMUDA</h3>
            <div class="jadwal-ibadah">
              <div class="jadwal-hari">Sabtu</div>
              <div class="jadwal-waktu">17.00 <br> WIB</div>
            </div>
          </div>
        </div>
        
        <div class="animasi-muncul">
          <div class="jadwal-box">
            <h3>SEKOLAH MINGGU</h3>
            <div class="jadwal-ibadah">
              <div class="jadwal-hari">Minggu</div>
              <div class="jadwal-waktu">08.30 <br> WIB</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="lokasi">
    <div class="lokasi-container">
      <h2 class="lokasi-title animasi-muncul">Lokasi Gereja</h2> 
    
      <div class="lokasi-content">
        <div class="lokasi-map animasi-muncul">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7979.635905488815!2d109.33939144527218!3d-0.024595071037587107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e1d5851e37e8f51%3A0xbe2f8f03517f20d9!2sGereja%20Yesus%20Sejati%20(GYS)%20Pontianak!5e0!3m2!1sid!2sid!4v1778079271551!5m2!1sid!2sid" 
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
        </div>

        <div class="lokasi-text animasi-muncul">
          <h3>Gereja Yesus Sejati <br> Pontianak</h3>
          <p>
            Jl. H. Abbas 1 No.27, Benua Melayu Darat, Kec. Pontianak Sel., Kota Pontianak, Kalimantan Barat 78243
          </p>
        </div>
      </div>
    </div>
  </section>
  
  <section class="doa" id="doa">
    <div class="doa-container animasi-muncul">
      <div class="doa-header">
        <i class="fa-solid fa-hands-praying doa-icon"></i>
        <h2>Permohonan Doa</h2>
        <p>Mari kita saling mendoakan. Sampaikan pokok doa Anda di bawah ini.</p>
      </div>
      
      <?php 
      if (isset($_GET['pesan_doa'])) {
        if ($_GET['pesan_doa'] == 'sukses') {
          echo '<div class="alert alert-success">Terima kasih! Permohonan doa Anda telah kami terima.</div>';
        } elseif ($_GET['pesan_doa'] == 'gagal') {
          echo '<div class="alert alert-danger">Terjadi kesalahan sistem, doa gagal dikirim.</div>';
        }
      }
      ?>

      <?php 
      if (isset($_SESSION['status']) && $_SESSION['status'] === 'login') : 
      ?>

      <form id="doa-form" class="doa-form" action="sv_doa.php" method="POST" novalidate>
          <div class="input-group">
            <label for="tanggal_doa">Tanggal</label>
            <input type="date" id="tanggal_doa" name="tanggal_doa" min="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
          </div>

        <div class="input-group">
            <label for="isi-doa">Pokok Doa</label>
            <textarea id="isi-doa" name="isi_doa" rows="5" placeholder="Tuliskan pokok doa Anda di sini..." required></textarea>
            <span class="error-msg" id="error-doa">Pokok doa tidak boleh kosong!</span>
          </div>

          <button type="submit" id="btn-submit-doa" class="btn-doa" disabled>Kirim Doa</button>
        </form>

      <?php else : ?>
        <div class="doa-logged-out-box">
          <i class="fa-solid fa-lock doa-lock-icon"></i>
          <p class="doa-login-notice">
            Anda harus <strong>Masuk (Login)</strong> terlebih dahulu untuk mengirimkan permohonan doa.
          </p>
          <a href="login.php" class="btn-klik btn-login-doa">Masuk / Daftar Akun</a>
        </div>
      <?php endif; ?>

    </div>
  </section>
</main>

<?php 
include "footer.php"; 
?>