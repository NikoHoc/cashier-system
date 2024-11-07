<?php
require_once("./config/database.php");
session_start();

if ($_SESSION['is_login'] == false) {
    header("location: login.php");
    exit;
}

// Assuming you store the logged-in user's username in the session
$loggedInUserId = $_SESSION['username'];

$user_query = "SELECT * FROM admin_depot WHERE username = ?";
$stmt = $db->prepare($user_query);
$stmt->bind_param("s", $loggedInUserId);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

if (!$user_data) {
    echo "User not found or an error occurred while fetching user data.";
    exit;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include "includes/head.php"; ?>
</head>

<body>
    <div class="wrapper">
        <aside id="sidebar">
            <!-- Sidebar -->
            <?php include "includes/sidebar.php"; ?>
            <!-- Sidebar -->
        </aside>

        <div class="main">
            <!-- Navbar -->
            <?php include "includes/navbar.php"; ?>
            <!-- Navbar -->

            <!-- Main Content -->
            <main class="content px-3 py-4">
                <div class="container-fluid card p-4">
                    <div class="row">
                        <div class="col-lg-6 text-center mt-5">
                            <div class="col-12 mb-5">
                                <img src="<?= isset($user_data['profile_pic']) ? './uploads/profile_pics/' . htmlspecialchars($user_data['profile_pic']) : './assets/images/default-pic.png' ?>" 
                                class="w-50 me-5" alt="Profile Picture">
                            </div>
                            <form class="col-12" method="POST" action="./services/functions/profile.php" enctype="multipart/form-data">
                                <div class="form-group">
                                    
                                    <input type="file" class="form-control-file" id="profilePic" name="profile_pic">
                                    <button type="submit" name="upload" class="btn btn-primary mt-3">Upload Foto</button>
                                </div>
                                
                            </form>
                        </div>

                        <div class="col-lg-6">
                            <h4 class="mb-3">Kelola Data User</h4>
                            <!-- Form Modifikasi -->
                            <form id="userForm" method="POST" action="./services/functions/user_functions.php">
                                <!-- Username (tetap disabled) -->
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input disabled type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" placeholder="Enter username">
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input disabled type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user_data['email_admin']) ?>" placeholder="Enter email">
                                </div>

                                <!-- Password with Show/Hide Toggle -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <input disabled type="password" class="form-control" id="password" name="password" value="<?= htmlspecialchars($user_data['password']) ?>" placeholder="Enter password">
                                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- No WA -->
                                <div class="mb-3">
                                    <label for="wa" class="form-label">No WA</label>
                                    <input disabled type="text" class="form-control" id="wa" name="wa" value="<?= htmlspecialchars($user_data['whatsapp_number']) ?>" placeholder="Enter WhatsApp number">
                                </div>

                                <!-- Alamat -->
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">Alamat</label>
                                    <textarea disabled class="form-control" id="alamat" name="alamat" rows="3" placeholder="Enter address"><?= htmlspecialchars($user_data['store_address']) ?></textarea>
                                </div>

                                <!-- Edit Button with Icon -->
                                <button type="button" id="editButton" class="btn btn-warning">
                                    <i class="fas fa-pencil-alt"></i> Edit Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
            <!-- Main Content -->
        </div>
    </div>

    <?php include "includes/script.php"; ?>

    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');

                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).html('<i class="fas fa-eye-slash"></i>');
                } else {
                    passwordField.attr('type', 'password');
                    $(this).html('<i class="fas fa-eye"></i>');
                }
            });

            $('#editButton').on('click', function() {
                const form = $('#userForm');
                const inputs = form.find('input:not(#username), textarea');

                // Toggle disabled state for all form fields except username
                inputs.each(function() {
                    $(this).prop('disabled', !$(this).prop('disabled'));
                });

                if ($(this).text().trim().includes('Edit Data')) {
                    $(this).html('<i class="fas fa-save"></i> Save Changes');
                    $(this).removeClass('btn-warning').addClass('btn-primary');
                } else {
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, simpan!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Pastikan semua field tidak disabled sebelum mengambil FormData
                            inputs.prop('disabled', false);

                            // Perform AJAX request to update data
                            const formData = new FormData(form[0]); // Access raw DOM element
                            $.ajax({
                                url: './services/functions/user_functions.php',
                                type: 'POST',
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(data) {
                                    console.log(data); // Tambahkan log untuk debugging
                                    try {
                                        const response = JSON.parse(data);
                                        if (response.success) {
                                            Swal.fire({
                                                title: 'Berhasil',
                                                text: 'Data berhasil diperbarui!',
                                                icon: 'success'
                                            }).then(() => {
                                                location.reload();
                                            });
                                        } else {
                                            Swal.fire({
                                                title: 'Gagal',
                                                text: 'Data gagal diperbarui. Silakan coba lagi!',
                                                icon: 'error'
                                            });
                                        }
                                    } catch (e) {
                                        console.error('Invalid JSON response:', data);
                                    }
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', error);
                                }
                            });

                            // Reset button text, icon, and background color
                            $(this).html('<i class="fas fa-pencil-alt"></i> Edit Data');
                            $(this).removeClass('btn-primary').addClass('btn-warning');
                        }
                    });
                }
            });
        });
    </script>




</body>

</html>