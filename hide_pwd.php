<!DOCTYPE html>
<html>
<head>
    <title>Show/Hide Password</title>
    <style>
        .input-box {
            position: relative;
            width: 250px;
        }
        .toggle-btn {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            border: none;
            background: none;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="input-box">
    <input type="password" id="password" placeholder="Enter Password">
    <button class="toggle-btn" onclick="togglePassword()">👁️</button>
</div>

<script>
function togglePassword() {
    var pass = document.getElementById("password");

    if (pass.type === "password") {
        pass.type = "text";      // Show password
    } else {
        pass.type = "password";  // Hide password
    }
}
</script>

</body>
</html>