<?php
// Include your DB connection file
include '../../Backend/db_connection.php';

// Assuming you have patient_id stored in session or somewhere
session_start();
$patient_id = $_SESSION['patient_id'] ?? null;
if (!$patient_id) {
    echo "<p>Please login to see appointments.</p>";
    exit;
}

// Fetch appointments for this patient, order by token number or date
$sql = "SELECT * FROM appointments WHERE patient_id = ? ORDER BY token_number ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="commonpage.css">
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
        <a class="navbar-item " href="PatientHomePage.html">Home</a>
        <a class="navbar-item is-active" href="MyAppointments.php">My Appointments</a>
        <a class="navbar-item" href="Profile.php">Profile</a>
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

    <main class="p-4">
        <!-- Trigger button -->
        <button class="button is-primary js-modal-trigger has-text-left	" data-target="modal-card-appointment">
            Book Appointment
        </button>

    </main>

<hr class="my-0">

<section class="section">
  <div class="container">
    <div class="level">
      <div class="level-left">
        <h2 class="title is-5">Upcoming Appointments</h2>
      </div>
      <div class="level-right">
        <span class="tag is-light is-link"><?php echo $result->num_rows; ?> Appointments</span>
      </div>
    </div>

    <div class="columns is-multiline">
      <?php
      if ($result->num_rows === 0) {
          echo "<p>No appointments found.</p>";
      } else {
          while ($row = $result->fetch_assoc()):
            // Check if appointment is upcoming or past (optional)
            $card_class = (strtotime($row['timestamp']) > time()) ? 'upcoming' : 'past';
      ?>
      <div class="column is-one-third">
        <div class="box appointment-card <?php echo $card_class; ?>">
          <p class="title is-6 has-text-danger"><?php echo htmlspecialchars($row['patient_name'] ?? 'Patient Name'); ?></p>
          <p><strong>Token Number:</strong> <?php echo htmlspecialchars($row['token_number']); ?></p>
          <p class="mb-1">
            <span class="icon is-small"><i class="fa-regular fa-calendar"></i></span>
            <span>Date: <?php echo date('d M Y', strtotime($row['timestamp'])); ?></span>
          </p>
          <p class="mb-1">
            <span class="icon is-small"><i class="fa-regular fa-clock"></i></span>
            <span>Shift: <?php echo htmlspecialchars($row['preferred_shift']); ?></span>
          </p>
          <div class="buttons mt-4">
            
            <button 
                class="button is-link is-light is-small js-modal-trigger" 
                data-target="reschedule-modal" 
                
                data-token="<?php echo htmlspecialchars($row['token_number'] ?? ''); ?>"
                data-name="<?php echo htmlspecialchars($row['patient_name'] ?? ''); ?>"
                data-age="<?php echo htmlspecialchars($row['patient_age'] ?? ''); ?>"
                data-gender="<?php echo htmlspecialchars($row['patient_gender'] ?? ''); ?>"
                data-mobile="<?php echo htmlspecialchars($row['patient_mobile_number'] ?? ''); ?>"
                data-email="<?php echo htmlspecialchars($row['patient_email'] ?? ''); ?>"
                data-shift="<?php echo htmlspecialchars($row['preferred_shift'] ?? ''); ?>"
                data-appointment-id="<?php echo htmlspecialchars($row['patient_id'] ?? ''); ?>"

                >
                <i class="fas fa-sync-alt mr-1"></i> Reschedule
            </button>

<script>
document.querySelectorAll('.js-modal-trigger[data-target="reschedule-modal"]').forEach(btn => {
  btn.addEventListener('click', function() {
    console.log('Reschedule button clicked:');
    console.log('token:', this.dataset.token);
    console.log('name:', this.dataset.name);
    console.log('age:', this.dataset.age);
    console.log('gender:', this.dataset.gender);
    console.log('mobile:', this.dataset.mobile);
    console.log('email:', this.dataset.email);
    console.log('shift:', this.dataset.shift);
    console.log('appointmentId:', this.dataset.appointmentId);
  });
});
</script>
            <button class="button is-danger is-light is-small js-modal-trigger" data-target="cancel-modal" data-token="<?php echo $row['token_number']; ?>">
              <i class="fas fa-times mr-1"></i> Cancel
            </button>
          </div>
        </div>
      </div>
      <?php endwhile; } ?>
    </div>
  </div>
</section>

    <!-- Modal Card -->
     <!--Modal Card for booking -->
    <div id="modal-card-appointment" class="modal">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Book Appointment</p>
                <button class="delete" aria-label="close"></button>
            </header>

            <section class="modal-card-body">
                <form action="../../Backend/submit_appointment.php" method="POST">
                    <div class="field">
                        <label class="label" >Patient's Name</label>
                        <div class="control">
                            <input class="input" type="text" placeholder="Enter Name" name="name">
                        </div>
                    </div>

                    <div class="field">
                        <label class="label" >Patient's Age</label>
                        <div class="control">
                            <input class="input" placeholder="Enter age" name="age"></input>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Patient's Gender</label>
                          <div class="control">
                              <div class="select">
                                  <select name="gender">
                                      <option selected>Select</option>
                                      <option>Male</option>
                                      <option>Female</option>
                                      <option>Other</option>
                                  </select>
                              </div>
                          </div>
                        
                    </div>
                    
                    <div class="field">
                        <label class="label" >Mobile No.</label>
                        <div class="control">
                            <input class="input" placeholder="Enter mobile number" name="mobile"></input>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label" >Email</label>
                        <div class="control">
                            <input class="input" placeholder="Enter Email" name="email"></input>
                        </div>
                    </div>

                    <div class="field">
                        <label class="label">Preferred Shift</label>
                        <div class="control">
                            <div class="select">
                                <select name="shift">
                                    <option value="">Select</option>
                                    <option value="Morning">Morning (8am-11am)</option>
                                    <option value="Afternoon">Afternoon (12pm-3pm)</option>
                                    <option value="Evening">Evening (4pm-7pm)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <footer class="modal-card-foot">
                <button class="button is-success">Submit</button>
                <button class="button">Cancel</button>
            </footer>
                </form>
            </section>

        </div>
    </div>


<!-- modal-card reschedule  -->
<div id="reschedule-modal" class="modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Reschedule Appointment</p>
      <button class="delete" aria-label="close"></button>
    </header>

    <!-- ✅ Only one form here -->
    <form action="../../Backend/reschedule_appoinment.php" method="POST">
      <section class="modal-card-body">
        <!-- Hidden input to store appointment ID -->
        <input type="hidden" name="token_number" id="reschedule-token-number">

        <div class="field">
          <label class="label">Patient's Name</label>
          <div class="control">
            <input class="input" type="text" placeholder="Enter Name" name="name" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Patient's Age</label>
          <div class="control">
            <input class="input" placeholder="Enter age" name="age" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Patient's Gender</label>
          <div class="control">
            <div class="select">
              <select name="gender" required>
                <option>Select</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
              </select>
            </div>
          </div>
        </div>

        <div class="field">
          <label class="label">Mobile No.</label>
          <div class="control">
            <input class="input" placeholder="Enter mobile number" name="mobile" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Email</label>
          <div class="control">
            <input class="input" placeholder="Enter Email" name="email" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Preferred Shift</label>
          <div class="control">
            <div class="select">
              <select name="preferred_shift">
                    <option value="">Select</option>
                    <option value="Morning">Morning (8am-11am)</option>
                    <option value="Afternoon">Afternoon (12pm-3pm)</option>
                    <option value="Evening">Evening (4pm-7pm)</option>
                </select>
            </div>
          </div>
        </div>
      </section>

      <footer class="modal-card-foot">
        <button type="submit" class="button is-success">Save changes</button>
        <button type="button" class="button cancel-button">Cancel</button>
      </footer>
    </form>
  </div>
</div>


<!-- modal card cancle -->
<!-- modal card cancel -->
<div id="cancel-modal" class="modal">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">Cancel Appointment</p>
      <button class="delete" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <form id="cancel-form" action="../../Backend/cancel_appointment.php" method="POST">
        <div class="field">
          <label class="label">Token Number</label>
          <div class="control">
            <input class="input" name="token_number" id="cancel-token-input" placeholder="Enter Token No." required>
          </div>
        </div>
        <footer class="modal-card-foot">
          <button type="submit" class="button is-danger">Cancel Appointment</button>
          <button type="button" class="button cancel-button">Close</button>
        </footer>
      </form>
    </section>
  </div>
</div>




    <!-- JavaScript to control modal -->
    <script>

        //reschedule modal functionality
        // This script fills the reschedule modal with data from the clicked button
       document.addEventListener('DOMContentLoaded', () => {
    // Select all reschedule buttons
    const rescheduleButtons = document.querySelectorAll('.js-modal-trigger[data-target="reschedule-modal"]');

    // Select modal input fields
    const modal = document.getElementById('reschedule-modal');
    const inputName = modal.querySelector('input[name="name"]');
    const inputAge = modal.querySelector('input[name="age"]');
    const selectGender = modal.querySelector('select[name="gender"]');
    const inputMobile = modal.querySelector('input[name="mobile"]');
    const inputEmail = modal.querySelector('input[name="email"]');
    const selectShift = modal.querySelector('select[name="preferred_shift"]');

    // ✅ Hidden input for token_number (correct name for backend)
    const inputTokenNumber = modal.querySelector('input[name="token_number"]');

    rescheduleButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Get data from clicked button
            const name = button.getAttribute('data-name');
            const age = button.getAttribute('data-age');
            const gender = button.getAttribute('data-gender');
            const mobile = button.getAttribute('data-mobile');
            const email = button.getAttribute('data-email');
            const shift = button.getAttribute('data-shift');
            const tokenNumber = button.getAttribute('data-token');

            // Fill modal inputs
            if (inputName) inputName.value = name || '';
            if (inputAge) inputAge.value = age || '';
            if (selectGender) selectGender.value = gender || 'Select';
            if (inputMobile) inputMobile.value = mobile || '';
            if (inputEmail) inputEmail.value = email || '';
            if (selectShift) selectShift.value = shift || 'Select';
            if (inputTokenNumber) inputTokenNumber.value = tokenNumber || '';

            // Open modal
            modal.classList.add('is-active');
        });
    });
});



                // Cancel modal functionality
                // This script fills the cancel modal with the token number from the clicked button
                document.addEventListener('DOMContentLoaded', () => {
                // Select all cancel buttons
                const cancelButtons = document.querySelectorAll('.js-modal-trigger[data-target="cancel-modal"]');
                const cancelModal = document.getElementById('cancel-modal');
                const cancelTokenInput = document.getElementById('cancel-token-input');

                cancelButtons.forEach(button => {
                    button.addEventListener('click', () => {
                    // Get token number from clicked button
                    const tokenNumber = button.getAttribute('data-token') || '';
                    // Fill token input in cancel modal
                    if (cancelTokenInput) {
                        cancelTokenInput.value = tokenNumber;
                    }
                    // Open the cancel modal
                    cancelModal.classList.add('is-active');
                    });
                });

                // Close modal on clicking close buttons inside cancel modal
                cancelModal.querySelectorAll('.delete, .cancel-button, .modal-background').forEach(el => {
                    el.addEventListener('click', () => {
                    cancelModal.classList.remove('is-active');
                    });
                });
                });




        document.addEventListener('DOMContentLoaded', () => {
            function openModal($el) {
                $el.classList.add('is-active');
            }

            function closeModal($el) {
                $el.classList.remove('is-active');
            }

            function closeAllModals() {
                (document.querySelectorAll('.modal') || []).forEach(($modal) => {
                    closeModal($modal);
                });
            }

            // Open modal
            (document.querySelectorAll('.js-modal-trigger') || []).forEach(($trigger) => {
                const modal = $trigger.dataset.target;
                const $target = document.getElementById(modal);

                $trigger.addEventListener('click', () => {
                    openModal($target);
                });
            });

            // Close modal
            (document.querySelectorAll('.modal-background, .modal-close, .modal-card-head .delete, .modal-card-foot .button') || []).forEach(($close) => {
                const $target = $close.closest('.modal');

                $close.addEventListener('click', () => {
                    closeModal($target);
                });
            });

            // Close on ESC
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeAllModals();
                }
            });
        });


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

 

</body>

</html>
