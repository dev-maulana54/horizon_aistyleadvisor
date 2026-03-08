<?php

$env = parse_ini_file(__DIR__ . '/.env');

$server   = $env['DB_HOST'];
$database = $env['DB_NAME'];

try {

    $dsn = "sqlsrv:Server=$server;Database=$database;TrustServerCertificate=yes";

    $conn = new PDO($dsn, "", "");

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Koneksi Windows Authentication BERHASIL";
} catch (PDOException $e) {

    echo "❌ Koneksi GAGAL: " . $e->getMessage();
}
