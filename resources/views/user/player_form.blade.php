<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Formulir Pemain - {{ $team->school_name }}</title>
</head>
<body>
<h2>Formulir Data Pemain - {{ $team->school_name }}</h2>

@if(session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<form action="{{ route('user.player.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="team_id" value="{{ $team->team_id }}">

    <label>NIK:</label>
    <input type="text" name="nik" required><br>

    <label>Nama Lengkap:</label>
    <input type="text" name="name" required><br>

    <label>Tanggal Lahir:</label>
    <input type="date" name="birthdate" required><br>

    <label>Jenis Kelamin:</label>
    <select name="gender" required>
        <option value="L">Laki-laki</option>
        <option value="P">Perempuan</option>
    </select><br>

    <label>Email:</label>
    <input type="email" name="email" required><br>

    <label>No. HP:</label>
    <input type="text" name="phone" required><br>

    <label>Asal Sekolah:</label>
    <input type="text" name="school" required><br>

    <label>Kelas:</label>
    <input type="text" name="grade" required><br>

    <label>Tahun STTB:</label>
    <input type="text" name="sttb_year" required><br>

    <label>Tinggi Badan (cm):</label>
    <input type="text" name="height" required><br>

    <label>Berat Badan (kg):</label>
    <input type="text" name="weight" required><br>

    <label>Ukuran Kaos:</label>
    <input type="text" name="tshirt_size" required><br>

    <label>Ukuran Sepatu:</label>
    <input type="text" name="shoes_size" required><br>

    <label>Posisi Basket:</label>
    <input type="text" name="basketball_position"><br>

    <label>Nomor Jersey:</label>
    <input type="text" name="jersey_number" required><br>

    <label>Instagram:</label>
    <input type="text" name="instagram" required><br>

    <label>TikTok :</label>
    <input type="text" name="tiktok" required><br>

    <label>Upload Akta Lahir:</label>
    <input type="file" name="birth_certificate" required><br>

    <label>Upload KK:</label>
    <input type="file" name="kk" required><br>

    <label>Upload SHUN:</label>
    <input type="file" name="shun" required><br>

    <label>Upload Kartu Identitas:</label>
    <input type="file" name="report_identity" required><br>

    <label>Upload Rapor Terakhir:</label>
    <input type="file" name="last_report_card" required><br>

    <label>Upload Foto Formal:</label>
    <input type="file" name="formal_photo" required><br>

    <label>Upload Surat Penugasan:</label>
    <input type="file" name="assignment_letter" required><br>

    <button type="submit">Submit Data Pemain</button>
</form>

</body>
</html>
