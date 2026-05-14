<?php
session_start();
include "header.php";
include "config.php";

if (!in_array(SITEADMIN, $_SESSION['LoggedInUserRoles'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT school_id, school_name, school_profile_image FROM schools");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Select School</title>

    <!-- Bootstrap -->
    <style>
        body {
            background-color: lightblue;
            font-family: 'Segoe UI', sans-serif;
        }

        .page-title {
            text-align: center;
            margin-top: 120px;
            font-weight: 600;
        }

        .school-container {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 25px;
            margin: 50px;
        }

        .school-card {
            background: #fff;
            border-radius: 15px;
            width: 100%;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .school-card:hover {
            transform: translateY(-10px) scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.25);
        }

        .school-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .school-name {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .subtitle {
            font-size: 13px;
            color: #777;
        }

        @media (max-width: 1200px) {
            .school-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .school-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .school-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .school-container {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .school-icon img {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 10px;
}
    </style>
</head>

<body>

    <h2 class="page-title fw-floder">Select Your School</h2>

    <div class="school-container">

        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="school-card" onclick="selectSchool(<?= $row['school_id'] ?>)">

                <div class="school-icon">
                    <img src="<?= !empty($row['school_profile_image'])
                        ?  htmlspecialchars($row['school_profile_image'])
                        : 'default-school.png' ?>" alt="School Image">
                </div>

                <div class="school-name">
                    <?= htmlspecialchars($row['school_name']) ?>
                </div>

                <div class="subtitle">Click to continue</div>

            </div>
        <?php endwhile; ?>

    </div>

    <script>
        function selectSchool(schoolId) {
            window.location = "set_school.php?school_id=" + schoolId;
        }
    </script>

    <?php include "footer.php"; ?>

</body>

</html>