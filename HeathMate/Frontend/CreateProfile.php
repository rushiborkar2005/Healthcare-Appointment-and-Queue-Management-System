<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css"/>    
    <link rel="stylesheet" href="loginPage.css">
</head>
<body>

    <img src="assets/Logo.png" alt="logo">

    <div class="main">
        <div class="background-img"></div>

    </div>

     <div class="login-card">
    <h1 class="title is-4">Create Profile</h1>

    <form action="../Backend/register.php" method="POST">
        <div class="field">
                        <label class="label">Who are you?</label>
                        <div class="control">
                            <div class="select">
                                <select name="role">
                                    <option value="" selected>Select</option>
                                    <option value="patient">Patient</option>
                                    <option value="doctor">Doctor</option>
                                </select>
                            </div>
                        </div>
                    </div>

            <div class="field">
                <div class="control">
                    <input class="input" type="text" id="username" placeholder="Enter Username" autocomplete="username" name="username" />
                </div>
                </div>

                <div class="field">
                <div class="control">
                    <input class="input" type="email" id="email" placeholder="Enter Email" autocomplete="email" name="email" />
                </div>
                </div>

                <div class="field">
                <div class="control">
                    <input class="input" type="password" id="password" placeholder="Create Password" autocomplete="current-password" name="password"/>
                </div>
                </div>
                
                <div class="field">
                <div class="control">
                    <input class="input" type="password" id="password" placeholder="Confom Password" autocomplete="current-password" name="confirm_password"/>
                </div>
                </div>

                <div class="buttons">
                <button class="button is-primary">Create account</button>
                </div>
            </div>
    </form>
    <div class="footer"></div>
</body>
</html>