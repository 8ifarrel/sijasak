function updateClock() {
    var elementsJam = document.getElementsByClassName("current-time");
    var now = new Date();
    var days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    var day = days[now.getDay()];
    var date = now.getDate();
    var months = [
        "Januari",
        "Februari",
        "Maret",
        "April",
        "Mei",
        "Juni",
        "Juli",
        "Agustus",
        "September",
        "Oktober",
        "November",
        "Desember",
    ];
    var month = months[now.getMonth()];
    var year = now.getFullYear();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var timeString =
        day +
        ", " +
        date +
        " " +
        month +
        " " +
        year +
        " | " +
        (hours < 10 ? "0" : "") +
        hours +
        ":" +
        (minutes < 10 ? "0" : "") +
        minutes +
        ":" +
        (seconds < 10 ? "0" : "") +
        seconds +
        " WITA ";

    for (var i = 0; i < elementsJam.length; i++) {
        elementsJam[i].textContent = timeString;
    }
}

updateClock();
setInterval(updateClock, 1000);
