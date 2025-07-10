<?php
session_start(); // REQUIRED to access session variables

require '../../Backend/db_connection.php'; // Adjust this path as needed

// ✅ Check if patient_id is stored in the session
if (!isset($_SESSION['patient_id'])) {
    echo "Patient not logged in.";
    exit(); // stop further execution
}

$patient_id = $_SESSION['patient_id'];
$row = [];

// ✅ Try to fetch from patients_profile
$query = "SELECT * FROM patients_profile WHERE patient_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Fallback to user_patients if no profile data
    $fallback_query = "SELECT * FROM user_patients WHERE patient_id = ?";
    $fallback_stmt = $conn->prepare($fallback_query);
    $fallback_stmt->bind_param("i", $patient_id);
    $fallback_stmt->execute();
    $fallback_result = $fallback_stmt->get_result();

    if ($fallback_result->num_rows > 0) {
        $fallback_data = $fallback_result->fetch_assoc();
        $row = [
            'patient_id' => $fallback_data['patient_id'] ?? '',
            'name' => $fallback_data['name'] ?? '',
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
<style>
  .centered {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      text-align: center;
    }
    .upload-btn {
      background-color: #1475f4;
      color: white;
      border-radius: 999px;
      font-weight: bold;
      padding: 0.75rem 2rem;
      border: none;
      cursor: pointer;
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

    
  <!-- Main Profile Section -->
  <section class="section">
    <div class="container">
      <div class="box">
        <div class="columns">
          <form action="../../Backend/update_profile.php" method="POST" enctype="multipart/form-data"> 
          <!-- Left: Profile Picture -->
          <!-- <div class="column is-one-quarter has-text-centered">
            <figure class="image is-128x128 is-inline-block">
              <img id="profileImage" class="is-rounded" src="../assets/default-profile.png" alt="Profile Picture">
            </figure>
            <p class="mt-4"><strong>Your ID:</strong></p> -->

            <!-- Upload Button -->
            <!-- <div class="file is-centered mt-3">
              <label class="file-label">
                <input class="file-input" type="file" name="image" id="fileInput" accept="image/*">
                <span class="file-cta upload-btn">
                  Upload Image
                </span>
              </label>
            </div>
          </div> -->

          <!-- Right: Profile Details -->
          <div class="column">
              <div class="columns is-multiline">
                <div class="column is-half">
                  <div class="field">
                    <label class="label">Name</label>
                    <div class="control">
                      <input class="input" type="text" placeholder="Name" name="name" >
                    </div>
                  </div>
                </div>

                <div class="column is-half">
                  <div class="field">
                    <label class="label">Gender</label>
                    <div class="control">
                      <div class="select is-fullwidth">
                        <select name="gender" >
                          <option selected>Select</option>
                          <option >Male</option>
                          <option >Female</option>
                          <option >Other</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="column is-half">
                  <div class="field">
                        <label class="label">Date of Birth</label>
                        <div class="control">
                            <input class="input" type="date" name="dob" >
                        </div>
                    </div>
                </div>

                <div class="column is-half">
                  <div class="field">
                    <label class="label">Mobile Number</label>
                    <div class="control">
                      <input class="input" type="disable" placeholder="Mobile No." name="mobile_number"  >
                    </div>
                  </div>
                </div>

                <div class="column is-full">
                  <div class="field">
                    <label class="label">Email</label>
                    <div class="control">
                      <input class="input" type="email" placeholder="Enter email" name="email" >
                    </div>
                  </div>
                </div>

                <div class="column is-full">
                  <div class="field">
                    <label class="label">Address</label>
                    <div class="control">
                      <textarea class="textarea" placeholder="Enter your permanent address" name="address" ></textarea>
                    </div>
                  </div>
                </div>
              </div>

              <div class="field is-grouped is-justify-content-end">
                <p class="control">
                  <button class="button is-success">Save</button>
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
<SCRIpt>
   // Bulma Navbar Script 
    document.addEventListener('DOMContentLoaded', () => {
      const burger = document.querySelector('.navbar-burger');
      const menu = document.querySelector('#navMenu');
      burger.addEventListener('click', () => {
        burger.classList.toggle('is-active');
        menu.classList.toggle('is-active');
      });
    });
</SCRIpt>
</html>