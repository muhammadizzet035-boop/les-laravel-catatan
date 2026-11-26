<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kontak</title>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; font-family: sans-serif;">
        <h2>Form Kontak</h2>

        @if(session('success'))
            <p style="color: green; font-weight: bold;">{{ session('success') }}</p>
        @endif

        <form action="/kontak" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label>Nama:</label><br>
                <input type="text" name="nama" style="width: 100%; padding: 8px; margin-top: 5px;" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Email:</label><br>
                <input type="email" name="email" style="width: 100%; padding: 8px; margin-top: 5px;" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label>Pesan:</label><br>
                <textarea name="pesan" rows="4" style="width: 100%; padding: 8px; margin-top: 5px;" required></textarea>
            </div>

            <button type="submit" style="padding: 10px 15px; background: #007bff; color: white; border: none; cursor: pointer;">Kirim Pesan</button>
        </form>
    </div>
</body>
</html>