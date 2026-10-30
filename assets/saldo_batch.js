window.onload = function () {
    // Menampilkan log bahwa script sudah dijalankan
    console.log("✅ saldo_batch.js aktif — menunggu auto klik...");

    // Tambahkan elemen status jika belum ada
    var statusArea = document.getElementById("statusBatch");
    if (!statusArea) {
        statusArea = document.createElement("div");
        statusArea.id = "statusBatch";
        statusArea.style.padding = "10px";
        statusArea.style.marginBottom = "10px";
        statusArea.style.backgroundColor = "#eaf4ff";
        statusArea.style.border = "1px solid #b6d4fe";
        statusArea.style.color = "#036;
        statusArea.style.fontFamily = "Arial, sans-serif";
        statusArea.innerHTML = "<strong>⏳ Menyiapkan auto-proses batch saldo...</strong>";
        document.body.insertBefore(statusArea, document.body.firstChild);
    }

    // Mulai auto klik setelah 3 detik
    setTimeout(function () {
        var nextButton = document.getElementById("nextBtn");
        if (nextButton) {
            statusArea.innerHTML = "<strong>▶️ Melanjutkan batch berikutnya...</strong>";
            nextButton.click();
        } else {
            statusArea.innerHTML = "<strong>❌ Tombol 'Lanjutkan' tidak ditemukan. Proses dihentikan.</strong>";
            console.log("Tombol dengan ID 'nextBtn' tidak tersedia.");
        }
    }, 3000); // dalam milidetik
};