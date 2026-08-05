<?php
$judul = 'Info Zakat Maal';
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1 class="halaman">Info &amp; Panduan Zakat Maal</h1>

    <div class="grid-2">
        <div class="card">
            <h2>Apa itu Zakat Maal?</h2>
            <p>Zakat maal adalah zakat yang dikenakan atas harta yang dimiliki seseorang ketika telah mencapai <strong>nisab</strong> (batas minimal) dan <strong>haul</strong> (telah dimiliki selama satu tahun). Zakat maal menjadi salah satu rukun Islam yang wajib ditunaikan bagi yang memenuhi syarat.</p>
        </div>
        <div class="card">
            <h2>Nisab &amp; Kadar</h2>
            <ul>
                <li><strong>Nisab :</strong> setara <strong><?= NISAB_GRAM ?> gram emas</strong></li>
                <li><strong>Kadar :</strong> <strong><?= KADAR_ZAKAT * 100 ?>%</strong> dari total harta</li>
                <li><strong>Haul :</strong> harta dimiliki selama 1 tahun qamariyah</li>
            </ul>
            <p style="margin-top:10px">Zakat = (Total Harta &times; <?= KADAR_ZAKAT * 100 ?>%) &mdash; dibayarkan jika total harta <strong>&ge; nisab</strong>.</p>
        </div>
    </div>

    <div class="card">
        <h2>Syarat Wajib Zakat Maal</h2>
        <ul>
            <li>Beragama <strong>Islam</strong>.</li>
            <li>Harta tersebut adalah <strong>milik penuh</strong> (haqul milk).</li>
            <li>Harta telah mencapai <strong>nisab</strong>.</li>
            <li>Harta telah mencapai <strong>haul</strong> (1 tahun) &mdash; kecuali untuk hasil pertanian &amp; rikaz.</li>
            <li>Bebas dari utang yang jatuh tempo.</li>
        </ul>
    </div>

    <div class="card">
        <h2>Jenis Harta yang Tergolong Zakat Maal</h2>
        <ul>
            <li>Emas, perak, logam mulia, dan barang berharga.</li>
            <li>Uang tunai dan tabungan/deposito.</li>
            <li>Hasil perdagangan / usaha.</li>
            <li>Rekening, saham, dan instrumen investasi lain.</li>
            <li>Hasil pertanian, perkebunan, dan peternakan.</li>
        </ul>
    </div>

    <div class="card">
        <h2>Golongan Penerima Zakat (8 Asnaf)</h2>
        <ol>
            <li>Fakir &ndash; orang yang tidak memiliki harta dan pekerjaan.</li>
            <li>Miskin &ndash; orang yang penghasilannya tidak mencukupi kebutuhan.</li>
            <li>Amil &ndash; pengelola zakat.</li>
            <li>Muallaf &ndash; orang yang baru masuk Islam.</li>
            <li>Riqab &ndash; hamba sahaya / budak.</li>
            <li>Gharim &ndash; orang yang terlilit utang.</li>
            <li>Fisabilillah &ndash; orang yang berjuang di jalan Allah.</li>
            <li>Ibnu Sabil &ndash; musafir yang kehabisan bekal.</li>
        </ol>
    </div>

    <p><a class="btn" href="kalkulator.php">&larr; Coba Kalkulator Zakat</a></p>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>