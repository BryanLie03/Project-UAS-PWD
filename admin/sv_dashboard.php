<?php
include "../security.php"; 
require_login();
require_role("admin");
include "../koneksi.php";

$aksi = $_GET['aksi'] ?? '';
$admin_aktif = $_SESSION['full_name'];

// --- Fungsi Pembantu Validasi Gambar ---
function validasiGambar($file) {
    $max_size = 7 * 1024 * 1024; // 7 MB
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if ($file['size'] > $max_size) return 'err_size';
    if (!in_array($file_ext, $allowed_ext)) return 'err_type';
    return 'ok';
}

// ==============================================================================
// 1. PROSES EVENT 
// ==============================================================================
if ($aksi == 'tambah_event') {
    $nama      = $_POST['nama_event'];
    $tanggal   = $_POST['tanggal_event']; 
    $deskripsi = trim($_POST['deskripsi_event']);
    $id_user   = $_SESSION['id_user'];
    $nama_file = ""; 

    if (isset($_FILES['gambar_event']['name']) && $_FILES['gambar_event']['name'] != '') {
        $validasi = validasiGambar($_FILES['gambar_event']);
        if ($validasi !== 'ok') {
            header("Location: dashboard.php?msg={$validasi}#event"); exit;
        }

        $dir_upload = '../uploads/event/';
        if (!is_dir($dir_upload)) mkdir($dir_upload, 0777, true);
        
        $nama_file = time() . '_' . basename($_FILES['gambar_event']['name']);
        move_uploaded_file($_FILES['gambar_event']['tmp_name'], $dir_upload . $nama_file);
    }

    if ($conn) {
        $stmt = $conn->prepare("INSERT INTO events (event, description, image, date, id_user) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nama, $deskripsi, $nama_file, $tanggal, $id_user);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?msg=event_sukses#event"); exit;
}

elseif ($aksi == 'edit_event') {
    $id_event  = (int)$_POST['id_event'];
    $nama      = $_POST['nama_event'];
    $tanggal   = $_POST['tanggal_event']; 
    $deskripsi = trim($_POST['deskripsi_event']);

    // Update dasar tanpa gambar
    $query = "UPDATE events SET event = ?, description = ?, date = ? WHERE id_event = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $nama, $deskripsi, $tanggal, $id_event);
    $stmt->execute();
    $stmt->close();

    // Jika upload gambar baru
    if (isset($_FILES['gambar_event']['name']) && $_FILES['gambar_event']['name'] != '') {
        $validasi = validasiGambar($_FILES['gambar_event']);
        if ($validasi !== 'ok') {
            header("Location: dashboard.php?msg={$validasi}#event"); exit;
        }

        // Hapus gambar lama
        $q_lama = mysqli_query($conn, "SELECT image FROM events WHERE id_event = $id_event");
        $row_lama = mysqli_fetch_assoc($q_lama);
        if (!empty($row_lama['image']) && file_exists('../uploads/event/' . $row_lama['image'])) {
            unlink('../uploads/event/' . $row_lama['image']);
        }

        // Simpan gambar baru
        $nama_file = time() . '_' . basename($_FILES['gambar_event']['name']);
        move_uploaded_file($_FILES['gambar_event']['tmp_name'], '../uploads/event/' . $nama_file);
        
        $stmt2 = $conn->prepare("UPDATE events SET image = ? WHERE id_event = ?");
        $stmt2->bind_param("si", $nama_file, $id_event);
        $stmt2->execute();
        $stmt2->close();
    }
    header("Location: dashboard.php?msg=event_edit_sukses#event"); exit;
}

elseif ($aksi == 'hapus_event') {
    $id_hapus = (int)$_GET['id'];
    if ($conn) {
        $query_gambar = mysqli_query($conn, "SELECT image FROM events WHERE id_event = $id_hapus");
        if ($row = mysqli_fetch_assoc($query_gambar)) {
            if (!empty($row['image']) && file_exists('../uploads/event/' . $row['image'])) {
                unlink('../uploads/event/' . $row['image']); 
            }
        }
        $stmt = $conn->prepare("DELETE FROM events WHERE id_event = ?");
        $stmt->bind_param("i", $id_hapus);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?msg=event_hapus#event"); exit;
}

elseif ($aksi == 'toggle_tampil_event') {
    $id_event = (int)$_POST['id_event'];
    $is_checked = (int)$_POST['is_checked'];

    if ($conn) {
        if ($is_checked == 1) {
            // Hitung total event yang sedang tampil (sudah dicentang sebelumnya)
            $q_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM events WHERE `show` = 1");
            $row_count = mysqli_fetch_assoc($q_count);
            
            if ($row_count['total'] >= 5) {
                // Beritahu JavaScript bahwa aksinya ditolak
                echo json_encode(['status' => 'error', 'msg' => 'Gagal: Anda hanya dapat memilih maksimal 5 event untuk ditampilkan di Halaman Depan!']);
                exit;
            }
            // Jika belum 5, izinkan pembaruan
            mysqli_query($conn, "UPDATE events SET `show` = 1 WHERE id_event = $id_event");
        } else {
            // Jika menghapus centang
            mysqli_query($conn, "UPDATE events SET `show` = 0 WHERE id_event = $id_event");
        }
        echo json_encode(['status' => 'success']);
    }
    exit;
}

// ==============================================================================
// 2. PROSES GALERI 
// ==============================================================================
elseif ($aksi == 'tambah_galeri') {
    $id_event = (int)$_POST['id_event'];
    $id_user  = $_SESSION['id_user']; 

    if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != '') {
        $validasi = validasiGambar($_FILES['foto']);
        if ($validasi !== 'ok') {
            header("Location: dashboard.php?msg={$validasi}#galeri"); exit;
        }

        $dir_upload = '../uploads/galeri/';
        if (!is_dir($dir_upload)) mkdir($dir_upload, 0777, true);
        $nama_file = time() . '_' . basename($_FILES['foto']['name']);

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $dir_upload . $nama_file)) {
            if ($conn) {
                $stmt = $conn->prepare("INSERT INTO galleries (image_gallery, id_event, id_user) VALUES (?, ?, ?)");
                $stmt->bind_param("sii", $nama_file, $id_event, $id_user);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    header("Location: dashboard.php?msg=galeri_sukses#galeri"); exit;
}

elseif ($aksi == 'edit_galeri') {
    $id_gallery = (int)$_POST['id_gallery'];
    $id_event = (int)$_POST['id_event'];

    if ($conn) {
        $stmt = $conn->prepare("UPDATE galleries SET id_event = ? WHERE id_gallery = ?");
        $stmt->bind_param("ii", $id_event, $id_gallery);
        $stmt->execute();
        $stmt->close();
    }

    if (isset($_FILES['foto']['name']) && $_FILES['foto']['name'] != '') {
        $validasi = validasiGambar($_FILES['foto']);
        if ($validasi !== 'ok') {
            header("Location: dashboard.php?msg={$validasi}#galeri"); exit;
        }

        $q_lama = mysqli_query($conn, "SELECT image_gallery FROM galleries WHERE id_gallery = $id_gallery");
        $row_lama = mysqli_fetch_assoc($q_lama);
        if (!empty($row_lama['image_gallery']) && file_exists('../uploads/galeri/' . $row_lama['image_gallery'])) {
            unlink('../uploads/galeri/' . $row_lama['image_gallery']);
        }

        $nama_file = time() . '_' . basename($_FILES['foto']['name']);
        move_uploaded_file($_FILES['foto']['tmp_name'], '../uploads/galeri/' . $nama_file);
        
        $stmt2 = $conn->prepare("UPDATE galleries SET image_gallery = ? WHERE id_gallery = ?");
        $stmt2->bind_param("si", $nama_file, $id_gallery);
        $stmt2->execute();
        $stmt2->close();
    }
    header("Location: dashboard.php?msg=galeri_edit_sukses#galeri"); exit;
}

elseif ($aksi == 'hapus_galeri') {
    $id_hapus = (int)$_GET['id'];
    if ($conn) {
        $query_gambar = mysqli_query($conn, "SELECT image_gallery FROM galleries WHERE id_gallery = $id_hapus");
        if ($row = mysqli_fetch_assoc($query_gambar)) {
            if (!empty($row['image_gallery']) && file_exists('../uploads/galeri/' . $row['image_gallery'])) {
                unlink('../uploads/galeri/' . $row['image_gallery']); 
            }
        }
        $stmt = $conn->prepare("DELETE FROM galleries WHERE id_gallery = ?");
        $stmt->bind_param("i", $id_hapus);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?msg=galeri_hapus#galeri"); exit;
}

    // ... Kode lama sv_dashboard ...

elseif ($aksi == 'hapus_event_force') {
    $id_hapus = (int)$_GET['id'];
    if ($conn) {
        // 1. Hapus SEMUA fisik foto galeri yang terikat
        $q_gal = mysqli_query($conn, "SELECT image_gallery FROM galleries WHERE id_event = $id_hapus");
        while ($row_gal = mysqli_fetch_assoc($q_gal)) {
            if (!empty($row_gal['image_gallery']) && file_exists('../uploads/galeri/' . $row_gal['image_gallery'])) {
                unlink('../uploads/galeri/' . $row_gal['image_gallery']);
            }
        }
        // 2. Hapus fisik foto Event
        $q_ev = mysqli_query($conn, "SELECT image FROM events WHERE id_event = $id_hapus");
        if ($row_ev = mysqli_fetch_assoc($q_ev)) {
            if (!empty($row_ev['image']) && file_exists('../uploads/event/' . $row_ev['image'])) {
                unlink('../uploads/event/' . $row_ev['image']);
            }
        }
        // 3. Hapus database Event (Otomatis Cascade hapus row Galeri)
        $stmt = $conn->prepare("DELETE FROM events WHERE id_event = ?");
        $stmt->bind_param("i", $id_hapus);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?msg=event_hapus#event"); exit; 
}

elseif ($aksi == 'hapus_galeri_massal') {
    if (isset($_POST['id_galeri_hapus']) && is_array($_POST['id_galeri_hapus'])) {
        foreach ($_POST['id_galeri_hapus'] as $id_g) {
            $id_bersih = (int)$id_g;
            // Hapus fisik gambar
            $q_gambar = mysqli_query($conn, "SELECT image_gallery FROM galleries WHERE id_gallery = $id_bersih");
            if ($row = mysqli_fetch_assoc($q_gambar)) {
                if (!empty($row['image_gallery']) && file_exists('../uploads/galeri/' . $row['image_gallery'])) {
                    unlink('../uploads/galeri/' . $row['image_gallery']); 
                }
            }
            // Hapus row DB
            mysqli_query($conn, "DELETE FROM galleries WHERE id_gallery = $id_bersih");
        }
    }
    header("Location: dashboard.php?msg=hapus_massal_sukses#galeri"); exit;
}

    
// ==============================================================================
// 3. PROSES DOA
// ==============================================================================
elseif ($aksi == 'konfirmasi_doa') {
    $id_pray = $_GET['id'] ?? 0;
    if ($id_pray > 0 && $conn) {
        $stmt = $conn->prepare("UPDATE prayers SET status = 'Dikonfirmasi' WHERE id_pray = ?");
        $stmt->bind_param("i", $id_pray);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?tab=Pending&msg=sukses_konfirmasi#data-doa"); exit;
}

elseif ($aksi == 'batal_konfirmasi_doa') {
    $id_pray = $_GET['id'] ?? 0;
    if ($id_pray > 0 && $conn) {
        $stmt = $conn->prepare("UPDATE prayers SET status = 'Pending' WHERE id_pray = ?");
        $stmt->bind_param("i", $id_pray);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: dashboard.php?tab=Dikonfirmasi&msg=sukses_batal_doa#data-doa"); exit;
}

elseif ($aksi == 'konfirmasi_semua') {
    if ($conn) {
        mysqli_query($conn, "UPDATE prayers SET status = 'Dikonfirmasi' WHERE status = 'Pending'");
    }
    header("Location: dashboard.php?tab=Pending&msg=sukses_semua#data-doa"); exit;
}

else {
    header("Location: dashboard.php");
    exit;
}
?>