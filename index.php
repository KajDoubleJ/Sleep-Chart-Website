<?php
    function get_data_from_file() {
        $sleep_data = array();
        if (($csv_file = fopen("test_sleep_data.csv", "r")) !== FALSE) { 
            while (($stats = fgetcsv($csv_file, null, ";")) !== FALSE) {
                $sleep_data[] = $stats;
            }
            fclose($csv_file);
        }  
        return $sleep_data;
    }

    function remove_utf8_bom($text)
    {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    function show_array($sleep_data) {
        for($i = 0; $i < count($sleep_data); $i++) {
            for($j = 0; $j < count($sleep_data[$i]); $j++) {
                echo $sleep_data[$i][$j].'&nbsp&nbsp';
            }
            echo '<br>';
        }
    }

    function is_valid_date($date_string) {
        $format = 'Y.m.d';
        $date = DateTime::createFromFormat($format, $date_string);
        return $date && $date->format($format) === $date_string;
    }

    function is_valid_hour_format($time) {
        if ($time != null) {
            $format = 'H:i';
            $new_time = strtotime($time);
            if ($new_time === false || date($format, $new_time) != $time) {
                return false;
            }
        }
        return true;
    }

    function is_valid_hours($sleep_data_row) {
        for($i = 1; $i < 7; $i++) {
            if (!is_valid_hour_format($sleep_data_row[$i])) {
                return false;
            }
        }
        for($i = 1; $i < 6; $i++) {
            if ($sleep_data_row[$i] >= $sleep_data_row[$i + 1] && $sleep_data_row[$i + 1] != null) {
                return false;
            }
            if ($i % 2 != 0 && 
                ($sleep_data_row[$i] != null && $sleep_data_row[$i + 1] == null) ||
                ($sleep_data_row[$i] == null && $sleep_data_row[$i + 1] != null)
            ) {
                return false;
            }
        }

        return true;
    }

    function is_valid_sleep_data($sleep_data) {
        if(empty($sleep_data) || count($sleep_data) == 1) {
            echo "Error! No data or it's empty";
            return false;
        }
        if($sleep_data[0][0] != 'date'   ||
           $sleep_data[0][1] != 'from_1' ||
           $sleep_data[0][2] != 'to_1'   ||
           $sleep_data[0][3] != 'from_2' ||
           $sleep_data[0][4] != 'to_2'   ||
           $sleep_data[0][5] != 'from_3' ||
           $sleep_data[0][6] != 'to_3'
        ) {
            echo 'Error! Wrong header format';
            return false;
        }
        $row_count = count($sleep_data);
        $compare_date = '1970.01.01';
        for($i = 1; $i < $row_count; $i++) {
            if (!is_valid_date($sleep_data[$i][0])) {
                echo 'Error! Wrong date format at row '.$i;
                return false;
            }
            if ($compare_date > $sleep_data[$i][0]) {
                echo 'Error! The date at row '.++$i.' is younger than previous date';
                return false;
            }
            $compare_date = $sleep_data[$i][0];
            if (!is_valid_hours($sleep_data[$i])
            ) {
                echo 'Error! Hours at row '.++$i.' are not valid';
                return false;
            }
        }
        return true;
    }
?>

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
            $sleep_data = get_data_from_file();
            $sleep_data[0] = remove_utf8_bom($sleep_data[0]);
            is_valid_sleep_data($sleep_data);
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