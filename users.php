<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

// User actions
if (isset($_POST['action'], $_POST['user_id'])) {

    $id = (int) $_POST['user_id'];

    if ($id !== (int) $_SESSION['user_id']) {

        if ($_POST['action'] === 'approve') {
            $status = 'approved';
            $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();

        } elseif ($_POST['action'] === 'disable') {
            $status = 'disabled';
            $stmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $id);
            $stmt->execute();

        } elseif ($_POST['action'] === 'delete') {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
        }
    }

    header("Location: users.php");
    exit;
}

$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
        }

        .navbar {
            background: #1e293b;
            color: white;
            padding: 20px 8%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h2 {
            font-size: 21px;
        }

        .nav-links {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            padding: 9px 14px;
            border-radius: 6px;
            background: #334155;
        }

        .nav-links a:hover {
            background: #475569;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header h1 {
            font-size: 27px;
        }

        .header p {
            color: #64748b;
            margin-top: 7px;
            font-size: 14px;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 7px;
            font-size: 14px;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 750px;
        }

        th {
            background: #f8fafc;
            color: #475569;
            text-align: left;
            padding: 15px;
            font-size: 13px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .approved {
            background: #dcfce7;
            color: #166534;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .disabled {
            background: #fee2e2;
            color: #991b1b;
        }

        .actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        button {
            border: none;
            padding: 8px 10px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 12px;
        }

        .approve-btn {
            background: #16a34a;
        }

        .disable-btn {
            background: #d97706;
        }

        .delete-btn {
            background: #dc2626;
        }

        .admin-label {
            color: #64748b;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .navbar {
                padding: 18px 5%;
                align-items: flex-start;
                gap: 12px;
                flex-direction: column;
            }

            .container {
                width: 94%;
                margin-top: 25px;
            }

            .table-container {
                padding: 12px;
            }
        }
    </style>
</head>

<body>

<div class="navbar">
    <h2>Admin Panel</h2>

    <div class="nav-links">
        <a href="dashboard.php">Dashboard</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">

    <div class="header">
        <div>
            <h1>User Management</h1>
            <p>Manage and monitor all registered user accounts.</p>
        </div>

        <a class="add-btn" href="add_user.php">+ Add User</a>
    </div>

    <div class="table-container">

        <table>

            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Status</th>
                <th>Date Created</th>
                <th>Actions</th>
            </tr>

            <?php while ($user = $result->fetch_assoc()): ?>

            <tr>
                <td><?= (int)$user['id'] ?></td>

                <td><?= htmlspecialchars($user['username']) ?></td>

                <td><?= htmlspecialchars($user['role']) ?></td>

                <td>
                    <span class="status <?= htmlspecialchars($user['status']) ?>">
                        <?= htmlspecialchars(ucfirst($user['status'])) ?>
                    </span>
                </td>

                <td><?= htmlspecialchars($user['created_at']) ?></td>

                <td>

                    <?php if ($user['id'] != $_SESSION['user_id']): ?>

                    <form method="POST" class="actions">

                        <input type="hidden" name="user_id"
                               value="<?= (int)$user['id'] ?>">

                        <button class="approve-btn"
                                type="submit"
                                name="action"
                                value="approve">
                            Approve
                        </button>

                        <button class="disable-btn"
                                type="submit"
                                name="action"
                                value="disable">
                            Disable
                        </button>

                        <button class="delete-btn"
                                type="submit"
                                name="action"
                                value="delete"
                                onclick="return confirm('Are you sure you want to delete this user?')">
                            Delete
                        </button>

                    </form>

                    <?php else: ?>

                        <span class="admin-label">Admin Account</span>

                    <?php endif; ?>

                </td>
            </tr>

            <?php endwhile; ?>

        </table>

    </div>

</div>

</body>
</html>