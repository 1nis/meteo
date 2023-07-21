<?php
$apiKey = ""; // Votre clé API OpenWeatherMap
$cityName = '';
$data = null;
$weather_main = '';
setlocale(LC_TIME, 'fr_FR.UTF8', 'fr.UTF8', 'fr_FR.UTF-8', 'fr.UTF-8');
if (isset($_POST['ville'])) {
    $cityName = $_POST['ville'];

    $googleApiUrl = "http://api.openweathermap.org/data/2.5/forecast?q=" . $cityName . "&lang=fr&units=metric&cnt=7&APPID=" . $apiKey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $weather_main = $data->list[0]->weather[0]->main;
}
$currentTime = time();
?>

<!doctype html>
<html>
<head>
<title>Prévisions météo pour la semaine</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
</head>
<body class="<?= strtolower($weather_main); ?>">
    <div class="container">
        <h1 class="title">Prévision Météo d'Anis</h1>
        <form method="post" action="" class="search-form">
            <div class="form-group">
                <input type="text" id="ville" name="ville" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Obtenir les prévisions météo</button>
        </form>

        <div class="weather-row">
        <?php 
        if ($data && isset($data->list)) {
            foreach ($data->list as $day => $value) { 
                // Définir la couleur de fond en fonction de la météo
                $weather_main = $value->weather[0]->main;
                if ($weather_main == 'Clear') {
                    $weather_color = 'clear';
                } elseif ($weather_main == 'Clouds') {
                    $weather_color = 'clouds';
                } elseif ($weather_main == 'Rain') {
                    $weather_color = 'rain';
                } else {
                    $weather_color = 'default';
                }
                ?>
                <div class="weather-container <?= $weather_color; ?>">
                    <h3 class="text-center"><?= strftime("%A %H:%M", $currentTime); ?></h3>
                    <h4 class="text-center"><?= $value->weather[0]->description; ?></h4>
                    <div class="text-center">
                        <img src="http://openweathermap.org/img/w/<?= $value->weather[0]->icon; ?>.png" class="weather-icon" />
                        <h2 class="temp"><?= $value->main->temp_max; ?>&deg;C</h2>
                    </div>
                    <div class="details">
                        <div>Humidité: <?= $value->main->humidity; ?> %</div>
                        <div>Vent: <?= $value->wind->speed; ?> km/h</div>
                    </div>
                </div>
            <?php 
                $currentTime += 86400;
            }
        } else if ($cityName != '') {
            echo "<p>Aucune donnée météo disponible pour cette ville.</p>";
        } ?>
        </div>
    </div>
    <script src="script.js"></script>
</body>
</html>
