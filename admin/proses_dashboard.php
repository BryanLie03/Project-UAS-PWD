<?php
session_start();

// ==============================================================================
// 0. KEAMANAN: Hanya admin yang boleh mengeksekusi file ini
// ==============================================================================
if (!isset($_SESSION['status']) || $_SESSION['status'] !== 'login' || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Anda bukan Administrator.");
}

$aksi = $_GET['aksi'] ?? '';
$admin_aktif = $_SESSION['full_name']; // Menggunakan 'full_name' dari sv_login.php

// ==============================================================================
// 1. PROSES VIDEO YOUTUBE
// ==============================================================================
if ($aksi == 'set_video') {
  // Mendapatkan URL thumbnail secara manual dari ID video
    $thumbnail_url = "https://img.youtube.com/vi/" . $_POST['id_video'] . "/maxresdefault.jpg";
    
    $data_video = [
    'id_video'      => $_POST['id_video'],
    'judul'         => $_POST['judul_video'], // Judul asli dari input
    'thumbnail'     => $thumbnail_url,
    'diupload_oleh' => $admin_aktif,
    'waktu'         => date('Y-m-d H:i:s')
    ];

    file_put_contents('data_video.json', json_encode($data_video));
    header("Location: dashboard.php?msg=video_sukses");
    exit;
}

elseif ($aksi == 'edit_judul_video') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && file_exists('data_video.json')) {
    // Ambil data lama
    $data_video = json_decode(file_get_contents('data_video.json'), true);
    
    // Update hanya bagian judul
    $data_video['judul'] = $_POST['judul_diedit'];
    $data_video['waktu'] = date('Y-m-d H:i:s'); // Update waktu edit
    
    // Simpan kembali
    file_put_contents('data_video.json', json_encode($data_video));
    header("Location: dashboard.php?msg=video_edit_sukses");
    exit;
    }
}

elseif ($aksi == 'hapus_video') {
    if (file_exists('data_video.json')) {
        unlink('data_video.json');
    }
    header("Location: dashboard.php?msg=video_hapus");
    exit;
}

// ==============================================================================
// 2. PROSES EVENT / KEGIATAN
// ==============================================================================
elseif ($aksi == 'tambah_event') {
    $event_baru = [
    'id'          => time(),
    'nama'        => $_POST['nama_event'],
    'tanggal'     => $_POST['tanggal_event'],
    'dibuat_oleh' => $admin_aktif
    ];
    
    $data_event = file_exists('data_event.json') ? json_decode(file_get_contents('data_event.json'), true) : [];
    array_unshift($data_event, $event_baru); // Masukkan ke urutan paling atas
    
    file_put_contents('data_event.json', json_encode($data_event));
    header("Location: dashboard.php?msg=event_sukses");
    exit;
}

elseif ($aksi == 'hapus_event') {
    $id_hapus = $_GET['id'];
    $data_event = file_exists('data_event.json') ? json_decode(file_get_contents('data_event.json'), true) : [];

  // Filter array untuk menghapus event dengan ID yang cocok
    $data_event = array_filter($data_event, function($e) use ($id_hapus) { 
    return $e['id'] != $id_hapus; 
    });

    file_put_contents('data_event.json', json_encode(array_values($data_event)));
    header("Location: dashboard.php?msg=event_hapus");
    exit;
}

// ==============================================================================
// 3. PROSES GALERI FOTO
// ==============================================================================
elseif ($aksi == 'tambah_galeri') {
  // Buat folder uploads jika belum ada
    if (!is_dir('uploads')) {
    mkdir('uploads', 0777, true);
    }

    $file_tmp = $_FILES['foto']['tmp_name'];
    $nama_file = time() . '_' . basename($_FILES['foto']['name']);
    $tujuan = 'uploads/' . $nama_file;

    if (move_uploaded_file($file_tmp, $tujuan)) {
    $galeri_baru = [
    'id'            => time(),
    'file'          => $tujuan,
    'judul'         => $_POST['judul_foto'],
    'diupload_oleh' => $admin_aktif
    ];
    
    $data_galeri = file_exists('data_galeri.json') ? json_decode(file_get_contents('data_galeri.json'), true) : [];
    array_unshift($data_galeri, $galeri_baru);
    
    file_put_contents('data_galeri.json', json_encode($data_galeri));
    header("Location: dashboard.php?msg=galeri_sukses");
    exit;
    } else {
    // Tangani jika upload gagal
    header("Location: dashboard.php?msg=galeri_gagal");
    exit;
    }
}

elseif ($aksi == 'hapus_galeri') {
    $id_hapus = $_GET['id'];
    $data_galeri = file_exists('data_galeri.json') ? json_decode(file_get_contents('data_galeri.json'), true) : [];
    
    foreach ($data_galeri as $key => $g) {
        if ($g['id'] == $id_hapus) {
        // Hapus file fisik dari folder uploads
        if (file_exists($g['file'])) {
            unlink($g['file']);
        }
        // Hapus data dari array
        unset($data_galeri[$key]);
        break;
        }
    }
    
  // Re-index array dan simpan kembali ke JSON
    file_put_contents('data_galeri.json', json_encode(array_values($data_galeri)));
    header("Location: dashboard.php?msg=galeri_hapus");
    exit;
}

// Jika tidak ada aksi yang cocok, kembalikan ke dashboard
else {
    header("Location: dashboard.php");
    exit;
}
?>