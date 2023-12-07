
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style >
            #popup2{
        text-align: center;
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #f4f4f4;
        padding: 20px;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        z-index: 9999;
    }
    #popup2 #bookingContent form {
        width: 70vh;
        background-color: #fff;
        padding: 20px;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        transition: box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    #popup2 #bookingContent h2 {
        text-align: center;
        color: #ff8c00;
    }

    #popup2 #bookingContent label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
        color: #333;
    }

    #popup2 #bookingContent input, textarea {
        text-align: center;
        width: calc(100% - 24px);
        height: 4vh;
        padding: 12px;
        margin-bottom: 15px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 5px;
        transition: border-color 0.3s ease;
    }
    textarea{
        resize: none;
        height: 7vh;
    }

    #popup2 #bookingContent input:focus, textarea:focus {
        border-color: #ff8c00;
    }

    #popup2 #bookingContent button {
        background-color: #ff8c00;
        color: #fff;
        padding: 15px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
        align-self: center;
    }

    #popup2 #bookingContent button:hover {
        background-color: #e07b00;
        transform: scale(1.05);
    }
       @media only screen and (max-width: 760px),
        (min-device-width: 802px) and (max-device-width: 1020px) {

            /* Force table to not be like tables anymore */
            table, thead, tbody, th, td, tr {
                display: block;

            }
            
            

            .empty {
                display: none;
            }

            /* Hide table headers (but not display: none;, for accessibility) */
            th {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            tr {
                border: 1px solid #ccc;
            }

            td {
                /* Behave  like a "row" */
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
            }



            /*
		Label the data
		*/
            td:nth-of-type(1):before {
                content: "Sunday";
            }
            td:nth-of-type(2):before {
                content: "Monday";
            }
            td:nth-of-type(3):before {
                content: "Tuesday";
            }
            td:nth-of-type(4):before {
                content: "Wednesday";
            }
            td:nth-of-type(5):before {
                content: "Thursday";
            }
            td:nth-of-type(6):before {
                content: "Friday";
            }
            td:nth-of-type(7):before {
                content: "Saturday";
            }


        }

        /* Smartphones (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
            body {
                padding: 0;
                margin: 0;
            }
        }

        /* iPads (portrait and landscape) ----------- */

        @media only screen and (min-device-width: 802px) and (max-device-width: 1020px) {
            body {
                width: 495px;
            }
        }

        @media (min-width:641px) {
            table {
                table-layout: fixed;
            }
            td {
                width: 33%;
            }
        }
        
        .row{
            margin-top: 20px;
        }
        
        .today{
            background:#eee;
        }
    </style>
</head>
<body>

<?php
    function build_calendar($month, $year) {
        $mysqli = new mysqli('localhost', 'root', '', 'bookingsysystem');
        $stmt = $mysqli->prepare("SELECT * FROM bookings_record WHERE MONTH(DATE) = ? AND YEAR(DATE) = ?");
        $stmt->bind_param('ss', $month, $year);
        $bookings = array();
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows>0){
                while($row = $result->fetch_assoc()){
                    $bookings[] = $row['DATE'];
                }
                
                $stmt->close();
            }
        }
        
        
         $daysOfWeek = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
         $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
         $numberDays = date('t',$firstDayOfMonth);
         $dateComponents = getdate($firstDayOfMonth);
         $monthName = $dateComponents['month'];
         $dayOfWeek = $dateComponents['wday'];
    
        $datetoday = date('Y-m-d');
       
        $calendar = "<table class='table table-bordered'>";
        $calendar .= "<center><h2>$monthName $year</h2>";
        $calendar.= "<a class='btn btn-xs btn-success' href='?month=".date('m', mktime(0, 0, 0, $month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month-1, 1, $year))."'>Previous Month</a> ";
        $calendar.= " <a class='btn btn-xs btn-danger' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a> ";
        $calendar.= "<a class='btn btn-xs btn-primary' href='?month=".date('m', mktime(0, 0, 0, $month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month+1, 1, $year))."'>Next Month</a></center><br>";
        
       
          $calendar .= "<tr>";
         foreach($daysOfWeek as $day) {
              $calendar .= "<th  class='header'>$day</th>";
         } 
    
         $currentDay = 1;
         $calendar .= "</tr><tr>";
    
    
         if ($dayOfWeek > 0) { 
             for($k=0;$k<$dayOfWeek;$k++){
                    $calendar .= "<td  class='empty'></td>"; 
    
             }
         }
        
         $month = str_pad($month, 2, "0", STR_PAD_LEFT);
      
         while ($currentDay <= $numberDays) {
    
              if ($dayOfWeek == 7) {
    
                   $dayOfWeek = 0;
                   $calendar .= "</tr><tr>";
    
              }
              
              $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
              $date = "$year-$month-$currentDayRel";
              
                $dayname = strtolower(date('l', strtotime($date)));
                $eventNum = 0;
                $today = $date==date('Y-m-d')? "today" : "";
             if($date<date('Y-m-d')){
                 $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs' disabled>N/A</button>";
             }elseif(in_array($date, $bookings)){
                 $calendar.="<td class='$today'><h4>$currentDay</h4> <button class='btn btn-danger btn-xs'> <span class='glyphicon glyphicon-lock
                 '></span> Already Booked</button>";
             }else{
                $calendar .= "<td class='$today'><h4>$currentDay</h4> <a href='#' class='btn btn-success btn-xs' onclick='showpopup2(\"$date\")'> <span class='glyphicon glyphicon-ok'></span> Book Now</a></td>";



             }
                
              $calendar .="</td>";
              $currentDay++;
              $dayOfWeek++;
         }
    
         if ($dayOfWeek != 7) { 
         
              $remainingDays = 7 - $dayOfWeek;
                for($l=0;$l<$remainingDays;$l++){
                    $calendar .= "<td class='empty'></td>"; 
             }
         }
         
         $calendar .= "</tr>";
         $calendar .= "</table>";
         echo $calendar;
}
?>

<div class="container">
    <!-- Replace '12' and '2023' with your desired month and year -->
    <?php build_calendar(12, 2023); ?>
</div>



<!-- old code -->
<div id="popup" style = "display:none;">
        <div class="popupContent">
            <p>
                <span class="close-btn" onclick="closePopup()">X</span>
                    If you have any questions, please call our locations on the following numbers: <br>
    
                    Haeri's Sizzling House - (0956) 156 9820 <br>
            
                    The Podium, Ortigas - ‭(0917) 702 8913 <br>
    
                    Bonifacio Global City - ‭(0917) 710 1682 <br>
    
                    City Of Dreams - (0956) 794 0075 <br>
    
                    Araneta City - (0977) 412 0670 <br>
    
                    Facebook: @HaerisSizzlingHouse <br>
    
                    Instagram: @HaerisSizzlingHouse<br>
    
                    Operating Hours:<br>
    
                    Newport World Resorts<br>
                    Bonifacio Global City<br>
    
                    11:00 am - 12:00 mn  Monday - Sunday<br>
    
                    The Podium<br>
    
                    Sunday - Thursday 10:00 AM - 11:00 PM<br>
                    Friday & Saturday 10:00AM - 12:00AM<br>
    
                    City Of Dreams<br>
    
                    11:00 am - 02:00 am  Monday - Sunday<br>
    
                    Delivery Hours:<br>
    
                    11:00 am - 8:00 pm<br>
                    5 pm cut-off for same-day delivery on all orders.<br>
    
                    <button id="order-here" onclick="showpopup2()">RESERVE NOW</button>
                </p>
            </div>
        </div>
        <div id="popup2">
            <div class="bookingContent" id="bookingContent">
                <span class="close-btn" onclick="closePopup()">X</span>
                <form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h2>Make a Reservation</h2>
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name"  value="<?php echo $name; ?>" required>
                
                    <label for="phone">Mobile Phone:</label>
                    <input type="tel" id="phone" name="phone"  value="<?php echo $phone; ?>"required>
                
                    <label for="date">Reservation Date:</label>
                    <input type="date" id="date" name="r_date" value="<?php echo $r_date; ?>" required>
                
                    <label for="time">Reservation Time:</label>
                    <input type="time" id="time" name="r_time"  value="<?php echo $r_time; ?>"required>
                
                    <label for="guest">Party Size (e.g., number of guests):</label>
                    <input type="number" id="guest" name="p_size"  value="<?php echo $p_size; ?>"required>
                
                    <label for="special-requests">Type your Request or  your pre-order here: <br>(leave blank if none)</label>
                    <textarea id="special-requests" name="c_order" rows="4"><?php echo $c_order; ?></textarea>                
                    <button type="submit">Make Reservation</button>
                </form>
            </div>
        </div>
                <script src = "script.js"></script>
</body>
</html>
