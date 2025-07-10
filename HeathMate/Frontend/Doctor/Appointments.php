<?php
include '../../Backend/db_connection.php';

// Query to fetch all appointments
// Query to fetch all appointments sorted by timestamp ascending
$sql = "SELECT * FROM appointments ORDER BY timestamp ASC";
$result = $conn->query($sql);

// Store fetched appointments into an array
$appointments = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="appoinments.css">
</head>
  <style>
    .appointment-card {
      border-left: 4px solid;
      padding: 1rem;
      height: 100%;
    }
    .upcoming { border-color: #E91E63; }  /* Pink border */
    .past { border-color: #00BCD4; }      /* Teal border */
    .card-footer .button {
      width: 100%;
    }
  </style>
<body>
    <!-- Header -->
  <section class="section py-3" style="background-color: #021E37;">
    <div class="container is-flex is-justify-content-space-between is-align-items-center is-flex-wrap-wrap">
      <img src="../assets/Logo.png" alt="logo" style="max-height: 60px" />
      <a class="button is-warning" href="../../Backend/logout.php">Logout</a>
    </div>
  </section>

  <!-- Navbar -->
  <nav class="navbar is-light" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="navMenu" class="navbar-menu">
      <div class="navbar-start">
        <a class="navbar-item " href="DoctorHomePage.html">Home</a>
        <a class="navbar-item is-active" href="Appointments.php">Appointments</a>
      </div> 

      <div class="navbar-end">
        <div class="navbar-item">
          <div class="field has-addons">
            <div class="control">
              <input class="input" type="text" placeholder="Search">
            </div>
            <div class="control">
              <button class="button is-info">Search</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
        <hr class="my-0">
    

    <section class="section">
    <div class="container">
      <h2 class="title has-text-centered">Today's Appointments </h2>

      <div class="table-container">
        <table class="table is-striped is-bordered is-hoverable is-fullwidth">
          <thead>
            <tr>
              <th>Token No</th>
              <th>Patient Name</th>
              <th>Age</th>
              <th>Gender</th>
              <th>Mobile</th>
              <th>Shift</th>
              <th>Appointment Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>

            <?php foreach ($appointments as $row): ?>
              <tr>
                <td><?= htmlspecialchars($row['token_number']) ?></td>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['patient_age']) ?></td>
                <td><?= htmlspecialchars($row['patient_gender']) ?></td>
                <td><?= htmlspecialchars($row['patient_mobile_number']) ?></td>
                <td><?= htmlspecialchars($row['preferred_shift']) ?></td>
                <td><?= date("Y-m-d H:i", strtotime($row['timestamp'])) ?></td>
                <td>
                  <form method="POST" action="../../Backend/DocCancel_Appointment.php">
                    <input type="hidden" name="token_number" value="<?= htmlspecialchars($row['token_number']) ?>">
                    <button type="submit" class="button is-small is-success">Done</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>

          </tbody>
        </table>
      </div>
    </div>
  </section>
    
</body>

</html>
