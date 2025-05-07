<?php
// A simple PHP page to test the LAMP stack and generate some metrics for Cloud Monitoring

// System information
$serverName = $_SERVER['SERVER_NAME'];
$serverSoftware = $_SERVER['SERVER_SOFTWARE'];
$phpVersion = phpversion();
$osInfo = php_uname();
$clientIP = $_SERVER['REMOTE_ADDR'];
$serverTime = date('Y-m-d H:i:s');

// Generate a bit of load to create metrics
$loadGenerator = function() {
    $startTime = microtime(true);
    $result = 0;
    // Simple calculation to generate CPU activity
    for ($i = 0; $i < 1000000; $i++) {
        $result += sin($i) * cos($i);
    }
    $endTime = microtime(true);
    return [
        'result' => $result,
        'time' => round($endTime - $startTime, 4)
    ];
};

// Only run the load generator if requested
$loadResults = null;
if (isset($_GET['generate_load']) && $_GET['generate_load'] === 'true') {
    $loadResults = $loadGenerator();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LAMP Stack with Cloud Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #0066cc;
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            display: inline-block;
            background-color: #0066cc;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
        .btn:hover {
            background-color: #004c99;
        }
        .load-results {
            background-color: #e9f7ef;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>LAMP Stack with Cloud Monitoring</h1>
        
        <p>This page demonstrates a working LAMP stack with Cloud Monitoring enabled. Use this page to generate some activity for your monitoring dashboards.</p>
        
        <h2>Server Information</h2>
        <table>
            <tr>
                <th>Server Name</th>
                <td><?php echo htmlspecialchars($serverName); ?></td>
            </tr>
            <tr>
                <th>Server Software</th>
                <td><?php echo htmlspecialchars($serverSoftware); ?></td>
            </tr>
            <tr>
                <th>PHP Version</th>
                <td><?php echo htmlspecialchars($phpVersion); ?></td>
            </tr>
            <tr>
                <th>Operating System</th>
                <td><?php echo htmlspecialchars($osInfo); ?></td>
            </tr>
            <tr>
                <th>Client IP Address</th>
                <td><?php echo htmlspecialchars($clientIP); ?></td>
            </tr>
            <tr>
                <th>Server Time</th>
                <td><?php echo htmlspecialchars($serverTime); ?></td>
            </tr>
        </table>
        
        <a href="?generate_load=true" class="btn">Generate Load</a>
        
        <?php if ($loadResults): ?>
        <div class="load-results">
            <h3>Load Test Results</h3>
            <p>Computation completed in <?php echo $loadResults['time']; ?> seconds.</p>
            <p>This activity should generate some CPU usage metrics in your Cloud Monitoring dashboard.</p>
        </div>
        <?php endif; ?>
        
        <h2>Cloud Monitoring Features Implemented</h2>
        <ul>
            <li>Ops Agent Installation</li>
            <li>Uptime Checks</li>
            <li>Alert Policies</li>
            <li>Custom Dashboards</li>
            <li>Log Analysis</li>
        </ul>
        
        <p>Check your Google Cloud Console to view the monitoring data collected from this instance.</p>
    </div>
</body>
</html>
