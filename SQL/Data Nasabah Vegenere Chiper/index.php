<!DOCTYPE html>
<html>
<head>
    <title>Enkripsi Vigenère Cipher</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Enkripsi Vigenère Cipher</h1>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nama = $_POST["nama"];
        $alamat = $_POST["alamat"];
        $password_plain = $_POST["password"];
        $key = $_POST["key"];

        // Hapus spasi dari password
        $password_plain = str_replace(" ", "", $password_plain);

        function vigenereEncrypt($password, $key) {
            $password = strtoupper($password);
            $key = strtoupper($key);
            $key_index = 0;
            $encrypted_password = "";
            
            for ($i = 0; $i < strlen($password); $i++) {
                $char = $password[$i];
                if (ctype_alpha($char)) {
                    $char_ascii = ord($char);
                    $key_char = $key[$key_index % strlen($key)];
                    $key_ascii = ord($key_char);
                    $encrypted_char = chr((($char_ascii - 65 + $key_ascii - 65) % 26) + 65);
                    $encrypted_password .= $encrypted_char;
                    $key_index++;
                } else {
                    $encrypted_password .= $char;
                }
            }
            
            return $encrypted_password;
        }

        $encrypted_password = vigenereEncrypt($password_plain, $key);

        // Koneksi ke database (ganti sesuai pengaturan Anda)
        $servername = "localhost";
        $username = "root";
        $password_db = "";
        $database = "bank_db";
        
        $conn = new mysqli("127.0.0.1", "root", "", "customer");
        
        if ($conn->connect_error) {
            die("Koneksi database gagal: " . $conn->connect_error);
        }
        
        // Masukkan data ke database
        $sql = "INSERT INTO customer (nama, alamat, password_plain, password_encrypted) VALUES ('$nama', '$alamat', '$password_plain', '$encrypted_password')";

        if ($conn->query($sql) === TRUE) {
            echo "<p>Data nasabah berhasil disimpan ke database.</p>";
        } else {
            echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
        }
        
        $conn->close();
    }
    ?>

    <form method="POST" action="">
        <label for="nama">Nama:</label>
        <input type="text" name="nama" required><br><br>
        <label for="alamat">Alamat:</label>
        <input type="text" name="alamat" required><br><br>
        <label for="password">Password:</label>
        <input type="text" name="password" required><br><br>
        <label for="key">Kunci Vigenère:</label>
        <input type="text" name="key" required><br><br>
        <input type="submit" value="Simpan Data">
    </form>
</body>
</html>
