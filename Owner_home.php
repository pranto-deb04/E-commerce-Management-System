<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

$total_users = 0;
$total_admins = 0;

$user_count_query = "SELECT COUNT(*) as total FROM users WHERE role = 'customer'";
$user_res = $conn->query($user_count_query);
if ($user_res) {
    $total_users = $user_res->fetch_assoc()['total'];
}

$admin_count_query = "SELECT COUNT(*) as total FROM users WHERE role = 'admin'";
$admin_res = $conn->query($admin_count_query);
if ($admin_res) {
    $total_admins = $admin_res->fetch_assoc()['total'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
</head>

<body
    style="margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; background-color: #f4f6f9; color: #333; display: flex; min-height: 100vh;">

    <div style="width: 260px; background-color: #1e293b; color: #fff; padding: 20px;">
        <h2 style="text-align: center; margin-bottom: 30px; font-size: 24px; color: #38bdf8;">Dashboard</h2>
        <a href="owner_home.php"
            style="display: block; color: #fff; background-color: #0f172a; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-bottom: 10px; transition: 0.3s;">Owner
            Overview</a>
        <a href="admin_management.php"
            style="display: block; color: #cbd5e1; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-bottom: 10px; transition: 0.3s;">Admin
            Management</a>
        <a href="logout.php"
            style="display: block; color: #ef4444; padding: 12px 15px; text-decoration: none; border-radius: 6px; margin-top: 50px; transition: 0.3s;">Logout</a>
    </div>

    <div style="flex: 1; padding: 40px;">
        <div style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; color: #0f172a; margin-bottom: 5px;">Owner System Panel</h1>
            <p style="color: #64748b; margin: 0;">Welcome back to your overview monitoring system.</p>
        </div>

        <div
            style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px;">
            <div
                style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <h4
                    style="color: #64748b; font-size: 14px; text-transform: uppercase; margin-bottom: 10px; margin-top: 0;">
                    Total Revenue</h4>
                <div style="font-size: 32px; font-weight: 700; color: #1e293b;">$0</div>
            </div>
            <div
                style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <h4
                    style="color: #64748b; font-size: 14px; text-transform: uppercase; margin-bottom: 10px; margin-top: 0;">
                    Total Users</h4>
                <div style="font-size: 32px; font-weight: 700; color: #1e293b;"><?php echo $total_users; ?></div>
            </div>
            <div
                style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <h4
                    style="color: #64748b; font-size: 14px; text-transform: uppercase; margin-bottom: 10px; margin-top: 0;">
                    Active Admins</h4>
                <div style="font-size: 32px; font-weight: 700; color: #1e293b;"><?php echo $total_admins; ?></div>
            </div>
            <div
                style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                <h4
                    style="color: #64748b; font-size: 14px; text-transform: uppercase; margin-bottom: 10px; margin-top: 0;">
                    Pending Orders</h4>
                <div style="font-size: 32px; font-weight: 700; color: #1e293b;">0</div>
            </div>
        </div>

        <div>
            <h3 style="margin-bottom: 20px; color: #1e293b;">Quick Management Actions</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
                <a href="admin_management.php"
                    style="background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); color: white; padding: 30px; border-radius: 10px; text-decoration: none; display: block;">
                    <h4 style="font-size: 20px; margin-bottom: 10px; margin-top: 0;">Manage Staff Accounts</h4>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Add new admins, change permissions, or analyze
                        team list logs.</p>
                </a>
                <a href="#"
                    style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); color: white; padding: 30px; border-radius: 10px; text-decoration: none; display: block;">
                    <h4 style="font-size: 20px; margin-bottom: 10px; margin-top: 0;">Financial Reports</h4>
                    <p style="margin: 0; opacity: 0.9; font-size: 14px;">Review daily sales data logs, gateways, and
                        transaction methods.</p>
                </a>
            </div>
        </div>
    </div>

</body>

</html>