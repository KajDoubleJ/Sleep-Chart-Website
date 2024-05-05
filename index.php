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

    function remove_utf8_bom($text) {
        $bom = pack('H*','EFBBBF');
        $text = preg_replace("/^$bom/", '', $text);
        return $text;
    }

    function clear_data($sleep_data) {
        $sleep_data[0] = remove_utf8_bom($sleep_data[0]);
        return $sleep_data;
    }

    function show_array($sleep_data) {
        for ($i = 0; $i < count($sleep_data); $i++) {
            for ($j = 0; $j < count($sleep_data[$i]); $j++) {
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
        for ($i = 1; $i < 7; $i++) {
            if (!is_valid_hour_format($sleep_data_row[$i])) {
                return false;
            }
        }
        for ($i = 1; $i < 6; $i++) {
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
        if (empty($sleep_data) || count($sleep_data) == 1) {
            echo "Error! No data or it's empty";
            return false;
        }
        if ($sleep_data[0][0] != 'date'  ||
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
        for ($i = 1; $i < $row_count; $i++) {
            if (!is_valid_date($sleep_data[$i][0])) {
                echo 'Error! Wrong date format at row '.$i;
                return false;
            }
            if ($compare_date > $sleep_data[$i][0]) {
                echo 'Error! The date at row '.++$i.' is younger than previous date';
                return false;
            }
            if ($compare_date == $sleep_data[$i][0]) {
                echo 'Error! The date at row '.++$i.' is the same as previous date';
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

    function time_difference_to_minutes($time1, $time2) {
        list($hours, $minutes) = explode(':', $time1);
        $minutes_time1 = $hours * 60 + $minutes;
        list($hours, $minutes) = explode(':', $time2);
        $minutes_time2 = $hours * 60 + $minutes;
        return abs($minutes_time2 - $minutes_time1);
    }

    function total_minutes_to_time_format($total_minutes) {
        $hours = (int)($total_minutes / 60);
        $minutes = $total_minutes % 60;
        $time_string = "";
        $time_string .= $hours . "h ";
        $time_string .= $minutes. "min";
        return $time_string;
    }

    function get_bar_margin_pixels($time, $MINUTES_PER_PIXEL_RATIO) {
        return intval(time_difference_to_minutes('00:00', $time) / $MINUTES_PER_PIXEL_RATIO);
    }

    function get_bar_length_pixels($sleep_time_minutes, $MINUTES_PER_PIXEL_RATIO) {
        return intval($sleep_time_minutes / $MINUTES_PER_PIXEL_RATIO + 1);
    }

    function get_sleep_period_html_string($from_margin_pixels, $sleep_length_pixels, $from, $to) {
        $SLEEP_HOUR_MARGIN_ALIGNMENT_PIXELS = 17;
        $html_sleep_period_string = '
            <div class="sleep" style="
                margin-left:'.$from_margin_pixels.'px; 
                width:'.$sleep_length_pixels.'px;
            ">
        ';
        if ($from != '00:00') {
            $left_sleep_hour_margin_pixels = -$SLEEP_HOUR_MARGIN_ALIGNMENT_PIXELS;
            $html_sleep_period_string .= '
                <p class="sleep_hour" style="
                    margin-left:'.$left_sleep_hour_margin_pixels.'px; 
                    top: -40px;
                ">'.$from.'</p>
            ';
        }
        if ($to != '23:59') {
            $right_sleep_hour_margin_pixels = $sleep_length_pixels - $SLEEP_HOUR_MARGIN_ALIGNMENT_PIXELS;
            $html_sleep_period_string .= '
                <p class="sleep_hour" style="
                    margin-left:'.$right_sleep_hour_margin_pixels.'px;
                    bottom: -40px;
                ">'.$to.'</p>
            ';
        }
        $html_sleep_period_string .= '</div>';
        return $html_sleep_period_string;
    }

    function render_diagram($sleep_data_row) {
        $MINUTES_PER_PIXEL_RATIO = 2;
        $BAR_WIDTH = intval(1440 / $MINUTES_PER_PIXEL_RATIO); 

        $date = $sleep_data_row[0];
        $from = array($sleep_data_row[1], $sleep_data_row[3], $sleep_data_row[5]);
        $to = array($sleep_data_row[2], $sleep_data_row[4], $sleep_data_row[6]);
        $total_sleep_minutes_of_day = 0;

        $from_margin_pixels = array();
        $sleep_length_pixels = array();

        for ($i = 0; $i < 3; $i++) {
            if ($from[$i] != NULL && $to[$i] != NULL) {
                $sleep_time_minutes = time_difference_to_minutes($from[$i], $to[$i]);
                $from_margin_pixels[] = get_bar_margin_pixels($from[$i], $MINUTES_PER_PIXEL_RATIO);
                $sleep_length_pixels[] = get_bar_length_pixels($sleep_time_minutes, $MINUTES_PER_PIXEL_RATIO);
                $total_sleep_minutes_of_day += $sleep_time_minutes;
            }
        }

        $all_sleep_periods_html_string = '';

        for ($i = 0; $i < count($from_margin_pixels); $i++) {
            $all_sleep_periods_html_string .= get_sleep_period_html_string(
                $from_margin_pixels[$i], 
                $sleep_length_pixels[$i], 
                $from[$i],
                $to[$i]
            );
        }

        $total_time_of_day = total_minutes_to_time_format($total_sleep_minutes_of_day);

        echo '
            <div class="day">
                <span class="day_number">'.$date.'</span>
                <span class="left_hour">00:00</span>
                <div class="bar" style="width:'.$BAR_WIDTH.'px;">
                    '.$all_sleep_periods_html_string.'
                </div>
                <span class="right_hour">23:59</span>
                <span class="total_time">'.$total_time_of_day.'</span>
            </div>
        ';
    }

    function show_data($sleep_data) {
        for ($i = 1; $i < count($sleep_data); $i++) {
            render_diagram($sleep_data[$i]);
        }
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
            $sleep_data = clear_data($sleep_data);
            if(is_valid_sleep_data($sleep_data)) {
                show_data($sleep_data);
            }
        ?>
    </div>
</body>
</html>