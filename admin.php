<html lang="en">
<?php include 'header.php'; ?>

<style>   
    .form-control {
        color: #fff;
        background-color: #ffffff00;
    }

    .form-control:focus {
        color: #fff;
        background-color: #ffffff00;
    }

    .form-control::placeholder {
        color: #fff !important;
        opacity: 1;     
    }
</style>

<div class="login-section">
    <div>
        <div class="row justify-content-center">
            <!-- <div class="col-md-8 col-lg-5"> -->
                <div class="login-box">
                    <h2 class="chinese-title text-sm-center text-white">Login</h2>
                    <?php 
                        $error = "";
                        if (isset($_GET['error'])) {
                            $error = $_GET['error'];
                        
                        }
                        if ($error != ""): ?>
                            <p class="text-danger bg-white"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    

                    <form method="post" action="validate_login.php">
                        <div class="input-group mb-3">
                            <div class=""></div>
                            <input type="text" class="form-control" placeholder="Username" name="username"  required>                         
                             <span class="input-group-text bg-primary" style="cursor:pointer;"><i class="fas fa-user text-white"></i></span>                            
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" class="form-control" placeholder="Password" 
                                name="password" id="loginpassword" required>
                            <span class="input-group-text bg-primary" style="cursor:pointer;" onclick="togglePassword('loginpassword', this)"> <i class="fas fa-eye text-white"></i></span>
                        </div>


                        <button type="submit" class="btn btn-login" style="margin-left: 34%;">Login</button>
                    </form>


                </div>
            <!-- </div> -->
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>

</html>