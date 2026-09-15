<?php
require_once 'db.php';
$showSuccess = false;
$errorMsg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password']; // Stored as plain text per request

    // Check if email exists
    $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $errorMsg = 'Email is already registered.';
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullName, $email, $password);
        
        if ($stmt->execute()) {
            $showSuccess = true;
        } else {
            $errorMsg = 'An error occurred. Please try again.';
        }
        $stmt->close();
    }
    $checkStmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - FlowChat</title>
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
        .success-overlay {
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease-in-out;
        }
        .success-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .success-icon {
            transform: scale(0.5);
            transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .success-overlay.active .success-icon {
            transform: scale(1);
        }
    </style>
</head>
<body class="text-white min-h-screen flex items-center justify-center relative overflow-hidden">
    
    <!-- Background Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-sky-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="w-full max-w-md px-5 relative z-10">
        <div class="text-center mb-10">
            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-sky-400 to-blue-600 flex items-center justify-center shadow-lg shadow-sky-500/20 mb-4">
                <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
            </div>
            <h1 class="text-3xl font-bold tracking-tight">Create your account</h1>
            <p class="text-slate-400 mt-2">Start connecting with your customers today.</p>
        </div>

        <div class="glass-panel rounded-2xl p-8 shadow-2xl">
            <?php if (!empty($errorMsg)): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4"></i> <?php echo $errorMsg; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Full Name</label>
                    <input type="text" name="full_name" required class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500/50 focus:bg-white/[0.05] transition-colors" placeholder="John Doe">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Email Address</label>
                    <input type="email" name="email" required class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500/50 focus:bg-white/[0.05] transition-colors" placeholder="you@company.com">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1.5">Password</label>
                    <input type="password" name="password" required class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-slate-500 focus:outline-none focus:border-sky-500/50 focus:bg-white/[0.05] transition-colors" placeholder="••••••••">
                </div>

                <button type="submit" class="w-full py-3.5 mt-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-semibold shadow-lg shadow-sky-500/20 transition-all hover:-translate-y-0.5">
                    Create Account
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-slate-400">
                Already have an account? <a href="login.php" class="text-sky-400 hover:text-sky-300 font-medium transition">Sign in</a>
            </div>
        </div>
    </div>

    <!-- Success Animation Overlay -->
    <div id="successOverlay" class="success-overlay fixed inset-0 z-50 bg-[#030712]/90 backdrop-blur-sm flex items-center justify-center">
        <div class="text-center success-icon">
            <div class="w-24 h-24 mx-auto bg-emerald-500/20 rounded-full flex items-center justify-center mb-6">
                <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center shadow-[0_0_40px_rgba(16,185,129,0.4)]">
                    <i data-lucide="check" class="w-8 h-8 text-white"></i>
                </div>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2">Account Created!</h2>
            <p class="text-slate-400">Redirecting you to login...</p>
        </div>
    </div>

    <script>
        lucide.createIcons();

        <?php if ($showSuccess): ?>
        // Trigger success animation if PHP sets $showSuccess to true
        document.addEventListener("DOMContentLoaded", () => {
            const overlay = document.getElementById('successOverlay');
            overlay.classList.add('active');
            
            // Redirect after 2.5 seconds
            setTimeout(() => {
                window.location.href = 'login.php';
            }, 2500);
        });
        <?php endif; ?>
    </script>
</body>
</html>
