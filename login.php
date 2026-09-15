<?php
session_start();
require_once 'db.php';
$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, full_name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Checking plain text password
        if ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['full_name'];
            header("Location: dashboard.php");
            exit();
        } else {
            $errorMsg = 'Invalid email or password.';
        }
    } else {
        $errorMsg = 'Invalid email or password.';
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - FlowChat</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #030712; }
        .glass-panel {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="text-white min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <div class="absolute top-0 right-1/4 w-[500px] h-[500px] bg-sky-600/10 rounded-full blur-[100px] pointer-events-none"></div>

    <div class="w-full max-w-md px-5 relative z-10">
        <div class="text-center mb-10">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center shadow-lg shadow-sky-500/20 mb-4">
                <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Welcome back</h1>
            <p class="text-slate-400 mt-2">Sign in to your FlowChat workspace.</p>
        </div>

        <div class="glass-panel rounded-2xl p-8 shadow-2xl">
            <?php if (!empty($errorMsg)): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" required class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500/50 focus:bg-white/[0.05] transition-colors" placeholder="you@company.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-300">Password</label>
                        <a href="#" class="text-xs text-sky-400 hover:text-sky-300 transition">Forgot password?</a>
                    </div>
                    <input type="password" name="password" required class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500/50 focus:bg-white/[0.05] transition-colors" placeholder="••••••••">
                </div>

                <button type="submit" class="w-full py-3.5 mt-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-semibold shadow-lg shadow-sky-500/20 transition-all hover:-translate-y-0.5">
                    Sign In
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-400">
                Don't have an account? <a href="register.php" class="text-sky-400 hover:text-sky-300 font-medium transition">Create one</a>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
