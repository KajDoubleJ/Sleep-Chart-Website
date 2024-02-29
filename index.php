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

    function show_array($sleep_data) {
        for($i = 0; $i < count($sleep_data); $i++) {
            for($j = 0; $j < count($sleep_data[$i]); $j++) {
                echo $sleep_data[$i][$j].'&nbsp&nbspd';
            }
            echo '<br>';
        }
    }

    function is_valid_date($date_string) {
        $format = 'Y.m.d';
        $date = DateTime::createFromFormat($format, $date_string);
        return $date && $date->format($format) === $date_string;
    }

    function is_validate_hours($from_1, $to_1, $from_2, $to_2, $from_3, $to_3) {
        $format = 'H:i';
        $time = strtotime($from_1);
        if ($time === false || date($format, $time) != $from_1) {
            return false;
        }
        $time = strtotime($to_1);
        if ($time === false || date($format, $time) != $to_1) {
            return false;
        }
        $time = strtotime($from_2);
        if ($time === false || date($format, $time) != $from_2) {
            return false;
        }
        $time = strtotime($to_2);
        if ($time === false || date($format, $time) != $to_2) {
            return false;
        }
        $time = strtotime($from_3);
        if ($time === false || date($format, $time) != $from_3) {
            return false;
        }
        $time = strtotime($to_3);
        if ($time === false || date($format, $time) != $to_3) {
            return false;
        }

        return true;
    }

    function is_valid_sleep_data($sleep_data) {
        if(empty($sleep_data) || count($sleep_data) == 1) {
            echo "Error! No data or it's empty";
            return false;
        }
        if(strpos($sleep_data[0][0], 'date') !== false   /*||
           $sleep_data[0][1] != "from_1" ||
           $sleep_data[0][2] != "to_1"   ||
           $sleep_data[0][3] != "from_2" ||
           $sleep_data[0][4] != "to_2"   ||
           $sleep_data[0][5] != "from_3" ||
           $sleep_data[0][6] != "to_3"*/
        ) {
            echo 'Error! Wrong header format <br>';
            echo $sleep_data[0][0] . ' : '.gettype($sleep_data[0][0]).'<br>';
            echo 'date' . ' : '.gettype('date').'<br>';
            return false;
        }
        $row_count = count($sleep_data);
        for($i = 1; $i <= $row_count; $i++) {
            if(!is_valid_date($sleep_data[$i][0])) {
                echo 'Error! Wrong date format at row '.$i;
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
            count($sleep_data[0]);
            //show_array($sleep_data);
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