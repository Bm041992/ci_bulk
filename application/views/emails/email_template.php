<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
      color: #333;
    }

    .container {
      max-width: 600px;
      margin: 20px auto;
      background: #ffffff;
      padding: 20px;
      border: 1px solid #ddd;
      border-radius: 6px;
    }
    .header {
        text-align: center;
        padding: 20px;
        border-bottom: 1px solid #eaeaea;
        background: #2e3f6e;
        color: #ffffff;
    }
    .header img {
        max-height: 60px;
        margin-bottom: 10px;
    }
    .header h2 {
        margin: 0;
        font-size: 22px;
        color: #ffffff; /* Changed from blue to white */
    }
    .event-info table, .invitee-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .event-info td {
      padding: 8px 5px;
    }

    .invitee-table th, .invitee-table td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    .invitee-table th {
      background-color: #f2f2f2;
    }

    .status-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      color: #fff;
      display: inline-block;
    }

    .status-pending { background-color: #6c757d; }
    .status-accepted { background-color: #28a745; }
    .status-rejected { background-color: #dc3545; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="<?php echo base_url('assets/images/ld_logo.png');?>" alt="Logo">
      <!-- <h2>Leaders Dimension</h2> -->
    </div>

    <!-- <p>Hello,</p> -->
    

    <div class="event-info" style="margin-top: 10px;">
        <?php echo $email_body;?>
    </div>

    <!-- <p style="margin-top: 20px;">Thank you,<br>Leaders Dimension Team</p> -->
  </div>
</body>
</html>
