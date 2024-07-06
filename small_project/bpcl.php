<!DOCTYPE html>
<html>
<head>
    <title>Task 3</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="test.css">
</head>
<body> 
    <div id="navbar">
        <div id="left">
            <img src="images/bpcllogo.jpeg" alt="logo" />
        </div>
        <div id="right">
            <nav>
                <a href="#">Home</a>
                <a href="#">About</a>
                <a href="#">Contact</a>
            </nav>
        </div>
    </div>

    <?php
$name = '';
$open = 0;
$close = 0;
$tdrate = 0;
$cashoff = 0;
$cashhand = 0;
$credit = 0;
$upi = 0;
$swipe = 0;
$test = 0;
$other = 0;
$fueltype = '';
$totalltr = 0;
$totalcash = 0;
$tally = 0;
$short = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    function getPostValue($key) {
        return isset($_POST[$key]) && $_POST[$key] !== '' ? max(floatval($_POST[$key]), 0) : 0;
    }

    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $open = getPostValue('open');
    $close = getPostValue('close');
    $tdrate = getPostValue('tdrate');
    $cashoff = getPostValue('cashoff');
    $cashhand = getPostValue('cashhand');
    $credit = getPostValue('credit');
    $upi = getPostValue('upi');
    $swipe = getPostValue('swipe');
    $test = getPostValue('test');
    $other = getPostValue('other');
    $fueltype = isset($_POST['fuel-type']) ? $_POST['fuel-type'] : '';

    // Perform calculations
    $totalltr = $close - $open;
    $totalcash = $totalltr * $tdrate;
    $tally = $cashoff + $cashhand + $credit + $upi + $swipe + $test + $other;
    $short = $tally - $totalcash;

    // Ensure $short remains a number
    if ($short > 0) {
        $short = $short;
    } elseif ($short == 0) {
        $short = 0;
    } 

    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "bpcldb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL to create table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS salesdata (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        open_read FLOAT,
        close_read FLOAT,
        today_rate FLOAT,
        cash_office FLOAT,
        cash_hand FLOAT,
        credit FLOAT,
        upi FLOAT,
        swipe FLOAT,
        test FLOAT,
        other FLOAT,
        fuel_type VARCHAR(50),
        total_ltr FLOAT,
        total_cash FLOAT,
        tally FLOAT,
        short FLOAT
    )";
    $conn->query($sql);

    // SQL to insert data
    $sql_insert = "INSERT INTO salesdata (name, open_read, close_read, today_rate, cash_office, cash_hand, credit, upi, swipe, test, other, fuel_type, total_ltr, total_cash, tally, short)
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);

    // Bind parameters and execute the statement
    if ($stmt) {
        $stmt->bind_param("sddddddddddsdddd", $name, $open, $close, $tdrate, $cashoff, $cashhand, $credit, $upi, $swipe, $test, $other, $fueltype, $totalltr, $totalcash, $tally, $short);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Data inserted successfully.";
        } else {
            echo "Error inserting data: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $conn->close();
}
?>


    <div class="main">
        <div class="main-lf">
            <!--  side bar content here -->
            <h2 class="main-lf-h2">DETAILS ENTERED</h2>
            <div class="table">
                <form>
                <table>
                    <tr>
                        <th>Name:</th>
                        <td><?php echo htmlspecialchars($name); ?></td>
                    </tr>
                    <tr>
                        <th>Opening Read:</th>
                        <td><?php echo htmlspecialchars($open); ?></td>
                    </tr>
                    <tr>
                        <th>Closing Read:</th>
                        <td><?php echo htmlspecialchars($close); ?></td>
                    </tr>
                    <tr>
                        <th>Total Ltr:</th>
                        <td><?php echo htmlspecialchars($totalltr); ?></td>
                    </tr>
                    <tr>
                        <th>Today Rate:</th>
                        <td><?php echo htmlspecialchars($tdrate); ?></td>
                    </tr>
                    <tr>
                        <th>Ltr x Rate =</th>
                        <td><?php echo htmlspecialchars($totalcash); ?></td>
                    </tr>
                    <tr>
                        <th>Fuel Type:</th>
                        <td><?php echo htmlspecialchars($fueltype); ?></td>
                    </tr>
                    <tr>
                        <th>Cash in Office:</th>
                        <td><?php echo htmlspecialchars($cashoff); ?></td>
                    </tr>
                    <tr>
                        <th>Cash in Your Hand:</th>
                        <td><?php echo htmlspecialchars($cashhand); ?></td>
                    </tr>
                    <tr>
                        <th>Credit:</th>
                        <td><?php echo htmlspecialchars($credit); ?></td>
                    </tr>
                    <tr>
                        <th>UPI:</th>
                        <td><?php echo htmlspecialchars($upi); ?></td>
                    </tr>
                    <tr>
                        <th>Swipe:</th>
                        <td><?php echo htmlspecialchars($swipe); ?></td>
                    </tr>
                    <tr>
                        <th>Test:</th>
                        <td><?php echo htmlspecialchars($test); ?></td>
                    </tr>
                    <tr>
                        <th>Other:</th>
                        <td><?php echo htmlspecialchars($other); ?></td>
                    </tr>
                    <tr>
                        <th style="background-color:red">Short:</th>
                        <td style="background-color:red"><?php echo htmlspecialchars($short); ?></td>
                    </tr>
                    <tr>
                        <th>Make Sure All Is Correct:</th>
                        <td><button class="button">Submit</button></td>  
                    </tr>
                </table>
                </form>
            </div>
        </div>
        <div class="main-rg">
            <h2>ENTER THE DETAILS BELOW</h2>
            <form class="form-grid" method="post">
                <div class="form-group">
                    <label for="name">Salesman Name:</label>
                    <input type="text" id="name" name="name" placeholder="Enter name">
                </div>
                <div class="form-group">
                    <label for="open">Opening Read:</label>
                    <input type="number" id="open" name="open" step="any" placeholder="Enter opening read">
                </div>
                <div class="form-group">
                    <label for="close">Closing Read:</label>
                    <input type="number" id="close" name="close" step="any" placeholder="Enter closing read">
                </div>
                <div class="form-group">
                    <label for="tdrate">Enter Today Rate:</label>
                    <input type="number" id="tdrate" name="tdrate" step="any" placeholder="Enter Today Rate">
                </div>
                <div class="form-group">
                    <label for="cashoff">Cash in Office:</label>
                    <input type="number" id="cashoff" name="cashoff" step="any" placeholder="Enter Cash in Office">
                </div>
                <div class="form-group">
                    <label for="cashhand">Cash in Your Hand:</label>
                    <input type="number" id="cashhand" name="cashhand" step="any" placeholder="Cash in Your Hand">
                </div>
                <div class="form-group">
                    <label for="credit">Credit:</label>
                    <input type="number" id="credit" name="credit" step="any" placeholder="Credit">
                </div>
                <div class="form-group">
                    <label for="upi">UPI:</label>
                    <input type="number" id="upi" name="upi" step="any" placeholder="UPI">
                </div>
                <div class="form-group">
                    <label for="swipe">Swipe:</label>
                    <input type="number" id="swipe" name="swipe" step="any" placeholder="Swipe">
                </div>
                <div class="form-group">
                    <label for="test">Test:</label>
                    <input type="number" id="test" name="test" step="any" placeholder="Test">
                </div>
                <div class="form-group">
                    <label for="other">Other:</label>
                    <input type="number" id="other" name="other" step="any" placeholder="Other">
                </div>
                <div class="form-group">
                    <label for="fuel-type">Fuel Type:</label>
                    <select id="fuel-type" name="fuel-type">
                        <option value="">Select Fuel Type</option>
                        <option value="diesel">Diesel</option>
                        <option value="petrol">Petrol</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="calculate">Calculate Sales:</label>
                    <input type="submit" value="Submit" name="submit-btn">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
