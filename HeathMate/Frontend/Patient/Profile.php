<?php
session_start(); // REQUIRED to access session variables
require '../../Backend/db_connection.php'; // Adjust this path as needed

if (!isset($_SESSION['patient_id'])) {
    echo "Patient not logged in.";
    exit();
}

$patient_id = $_SESSION['patient_id'];

// ✅ Serve image directly if requested
if (isset($_GET['show_image'])) {
    $stmt = $conn->prepare("SELECT image FROM patients_profile WHERE patient_id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $imageData = $row['image'];

        if ($imageData) {
            header("Content-Type: image/jpeg"); // Or image/png based on uploaded image
            echo $imageData;
        } else {
            header("Content-Type: image/png");
            readfile("../assets/default_profile.png");
        }
    } else {
        http_response_code(404);
        echo "Image not found.";
    }

    $stmt->close();
    $conn->close();
    exit(); // Important: stop script after image output
}

// ✅ Otherwise, load profile data and render page
$row = [];
$query = "SELECT * FROM patients_profile WHERE patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    $fallback_query = "SELECT * FROM user_patients WHERE patient_id = ?";
    $fallback_stmt = $conn->prepare($fallback_query);
    $fallback_stmt->bind_param("i", $patient_id);
    $fallback_stmt->execute();
    $fallback_result = $fallback_stmt->get_result();

    if ($fallback_result->num_rows > 0) {
        $fallback_data = $fallback_result->fetch_assoc();
        $row = [
            'patient_id' => $fallback_data['patient_id'] ?? '',
            'username' => $fallback_data['username'] ?? '',
            'email' => $fallback_data['email'] ?? '',
            'gender' => '',
            'date_of_birth' => '',
            'mobile_number' => '',
            'address' => '',
        ];
    } else {
        echo "No user record found.";
        exit();
    }
}

if (isset($stmt)) $stmt->close();
if (isset($fallback_stmt)) $fallback_stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="PatientHomePage.css">
</head>

<body>
      <!-- Header -->
  <section class="section py-3" style="background-color: #021E37;">
    <div class="container is-flex is-justify-content-space-between is-align-items-center is-flex-wrap-wrap">
      <img src="../assets/Logo.png" alt="logo" style="max-height: 60px" />
      <a class="button is-warning" href="../../Backend/logout.php">Logout</a>
    </div>
  </section>

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
        <a class="navbar-item " href="PatientHomePage.html">Home</a>
        <a class="navbar-item " href="MyAppointments.php">My Appointments</a>
        <a class="navbar-item is-active" href="Profile.php">Profile</a>
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

    </div>

    
  <!-- Main Profile Section -->
  <section class="section">
    <div class="container">
      <div class="box">
        <div class="columns">
          <!-- Left: Profile Picture -->
          <!-- <div class="column is-one-quarter has-text-centered">
            <figure class="image is-128x128 is-inline-block">
              <img class="is-rounded" src="?show_image=1" alt="Profile Picture">
            </figure>
            <p class="mt-4"><strong><?php echo htmlspecialchars($row['name'] ?? 'null'); ?></strong></p>
            <p class="has-text-grey">Your ID: <?php echo htmlspecialchars($row['patient_id'] ?? 'null'); ?></p>
          </div> -->

          <!-- Right: Profile Details -->
          <div class="column">
            <form action="CreateProfile.php" method="POST">
              <div class="columns is-multiline">
                <div class="column is-half">
                  <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                      <input class="input" type="text" placeholder="Name" name="name" value="<?php echo htmlspecialchars($row['name'] ?? ''); ?>" disabled>
                    </div>
                  </div>
                </div>

                <div class="column is-half">
                  <div class="field">
                    <label class="label">Gender</label>
                    <div class="control">
                      <div class="select is-fullwidth">
                        <select name="gender" disabled>
                          <option selected>Select</option>
                          <option value="Male" <?php if(($row['gender'] ?? '') == 'Male') echo 'selected'; ?>>Male</option>
                          <option value="Female" <?php if(($row['gender'] ?? '') == 'Female') echo 'selected'; ?>>Female</option>
                          <option value="Other" <?php if(($row['gender'] ?? '') == 'Other') echo 'selected'; ?>>Other</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="column is-half">
                  <div class="field">
                        <label class="label">Date of Birth</label>
                        <div class="control">
                            <input class="input" type="date" name="date_of_birth" value="<?php echo htmlspecialchars($row['dob'] ?? ''); ?>" disabled>
                        </div>
                    </div>
                </div>

                <div class="column is-half">
                  <div class="field">
                    <label class="label">Mobile Number</label>
                    <div class="control">
                      <input class="input" type="disable" placeholder="Mobile No." name="mobile_number" value=" <?php echo htmlspecialchars($row['mobile_number'] ?? 'null'); ?>
" disabled>
                    </div>
                  </div>
                </div>

                <div class="column is-full">
                  <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                      <input class="input" type="email" placeholder="Enter email" name="email" value="<?php echo htmlspecialchars($row['email'] ?? 'null'); ?>
" disabled>
                    </div>
                  </div>
                </div>

                <div class="column is-full">
                  <div class="field">
                    <label class="label">Address</label>
                    <div class="control">
                      <textarea class="textarea" placeholder="Enter your permanent address" name="address" disabled><?php echo htmlspecialchars($row['address'] ?? 'null'); ?></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-grouped is-justify-content-end">
                <p class="control">
                  <button class="button is-success">Update Profile</button>
                </p>
                <p class="control">
                  <button class="button is-light" type="reset">Cancel</button>
                </p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
   <!-- Footer -->
  <footer class="footer">
    <div class="content has-text-centered">
      <p>&copy; 2025 Your Health Platform. All rights reserved.</p>
    </div>
  </footer>
</body>
<script>
   // Bulma Navbar Script 
    document.addEventListener('DOMContentLoaded', () => {
      const burger = document.querySelector('.navbar-burger');
      const menu = document.querySelector('#navMenu');
      burger.addEventListener('click', () => {
        burger.classList.toggle('is-active');
        menu.classList.toggle('is-active');
      });
    });
</script>

</html>