<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $people = [];
    for ($i = 1; $i <= 5; $i++) {
        $name = $_POST["nama$i"] ?? '';
        $height = $_POST["tinggi$i"] ?? '';
        if ($name && $height) {
            $people[] = ["nama" => $name, "tinggi" => (int)$height];
        }
    }
    file_put_contents('data.json', json_encode($people, JSON_PRETTY_PRINT));
}
$data = file_exists('data.json') ? json_decode(file_get_contents('data.json'),
 true) : [];
$labels = array_column($data, 'nama');
$heights = array_column($data, 'tinggi');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Perbandingan Tinggi Badan</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h1>Perbandingan Tinggi Badan</h1>
    <h2>Masukkan Data (maksimal 5 orang)</h2>
    <form method="post">

        <?php for ($i = 1; $i <= 5; $i++): ?>
            <input type="text" name="nama<?= $i ?>" placeholder="Nama <?= $i ?>">
            <input type="number" name="tinggi<?= $i ?>" placeholder="Tinggi (cm)">
            <br>
        <?php endfor; ?>
        <button type="submit">Simpan & Tampilkan Grafik</button>
    </form>

    <?php if (!empty($data)): ?>
        <canvas id="heightChart"></canvas>
        <script>
            const ctx = document.getElementById('heightChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels) ?>,
                    datasets: [{
                        label: 'Tinggi Badan (cm)',
                        data: <?= json_encode($heights) ?>,
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                        borderWidth: 1
                    }]

                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 200
                        }
                    }
                }
            });
        </script>
    <?php endif; ?>

    <div class="footer">
        &copy; <?= date('Y') ?> Perbandingan Tinggi - Dibuat oleh Nim anda & Nama Anda
    </div>
</body>

</html>