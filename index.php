<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Badanie snu</title>

    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="content">
        <?php
            $start_row = 1;
            if (($csv_file = fopen("sleep_data.csv", "r")) !== FALSE) { 
                while (($read_data = fgetcsv($csv_file, 1000, ";")) !== FALSE) {
                    $column_count = count($read_data);
                    $start_row++;
                    for ($c=0; $c < $column_count; $c++) {
                        echo $read_data[$c];
                    }
                }
                fclose($csv_file);
            }
        ?>
        <!-- <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div>

        <div class="day">
            <span class="day_number">2023.10.16</span>
            <span class="left_hour">0:00</span>
            <div class="bar">
                <div class="sleep">
                    <p class="sleep_hour">14:00</p>
                </div>
            </div>
            <span class="right_hour">23:59</span>
        </div> -->
    </div>
</body>
</html>