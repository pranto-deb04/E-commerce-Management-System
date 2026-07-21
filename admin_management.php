<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin'])) {
    $name = trim($_POST['admin_name']);
    $email = trim($_POST['admin_email']);
    $password = $_POST['admin_password'];
    $role = 'admin';

    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "<div style='padding: 10px; background-color: #fef2f2; color: #ef4444; border-radius: 6px; margin-bottom: 15px;'>Email already exists!</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {
            $message = "<div style='padding: 10px; background-color: #f0fdf4; color: #15803d; border-radius: 6px; margin-bottom: 15px;'>New Admin Added Successfully!</div>";
        } else {
            $message = "<div style='padding: 10px; background-color: #fef2f2; color: #ef4444; border-radius: 6px; margin-bottom: 15px;'>Failed to add admin.</div>";
        }
    }
}

$admins_sql = "SELECT id, name, email FROM users WHERE role = 'admin' ORDER BY id DESC";
$admins_result = $conn->query($admins_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Management</title>
</head>
<body style="margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; color: #333; display: flex; min-height: 100vh;">

    <div style="width: 260px; background-color: #1e293b; color: #fff; padding: 20px;">
        <h2 style="text-align: center; margin-bottom: 30px; font-size: 24px; color: #38bdf8;">Dashboard</h2>
        <a href="owner_home.php" style="display: block; color: #cbd5e1; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-bottom: 10px; transition: 0.3s;">Owner Overview</a>
        <a href="admin_management.php" style="display: block; color: #fff; background-color: #0f172a; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-bottom: 10px; transition: 0.3s;">Admin Management</a>
        <a href="logout.php" style="display: block; color: #ef4444; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-top: 50px; transition: 0.3s;">Logout</a>
    </div>

    <div style="flex: 1; padding: 40px;">
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; color: #0f172a; margin-bottom: 5px;">Admin Management</h1>
            <p style="color: #64748b; margin: 0;">Add, edit, or remove administrators for your system.</p>
        </div>

        <?php if (!empty($message)) echo $message; ?>

        <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); margin-bottom: 30px;">
            <h3 style="margin-bottom: 20px; color: #1e293b;">Create New Admin Account</h3>
            <form action="admin_management.php" method="POST">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-bottom: 15px;">
                    <input type="text" name="admin_name" placeholder="Full Name" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <input type="email" name="admin_email" placeholder="Email Address" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                    <input type="password" name="admin_password" placeholder="Password" required style="width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; outline: none;">
                </div>
                <button type="submit" name="add_admin" style="background-color: #2563eb; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Add Admin</button>
            </form>
        </div>

        <div style="background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background-color: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <th style="padding: 15px; color: #64748b;">ID</th>
                        <th style="padding: 15px; color: #64748b;">Name</th>
                        <th style="padding: 15px; color: #64748b;">Email</th>
                        <th style="padding: 15px; color: #64748b;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($admins_result && $admins_result->num_rows > 0): ?>
                        <?php while ($row = $admins_result->fetch_assoc()): ?>
                            <tr style="border-bottom: 1px solid #e2e8f0;">
                                <td style="padding: 15px;"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td style="padding: 15px; font-weight: 600;"><?php echo htmlspecialchars($row['name']); ?></td>
                                <td style="padding: 15px; color: #64748b;"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td style="padding: 15px;">
                                    <a href="delete_admin.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this admin?');" style="color: #ef4444; text-decoration: none; font-weight: 500;">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" style="padding: 20px; text-align: center; color: #64748b;">No admin accounts found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>