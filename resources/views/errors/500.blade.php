<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;background:#F0F4FA;display:flex;align-items:center;justify-content:center;padding:2rem;-webkit-font-smoothing:antialiased}
        .wrap{text-align:center;max-width:480px}
        .code{font-family:'Syne',sans-serif;font-size:clamp(5rem,20vw,9rem);font-weight:800;line-height:1;letter-spacing:-.06em;background:linear-gradient(135deg,#E11D48,#FB7185);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:.25rem}
        .icon-ring{width:80px;height:80px;border-radius:50%;background:#fff;border:2px solid #FFE4E6;display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#E11D48;margin:0 auto 1.5rem;box-shadow:0 8px 30px rgba(225,29,72,.12)}
        h1{font-family:'Syne',sans-serif;font-size:1.5rem;font-weight:800;color:#0D1117;margin-bottom:.4rem}
        p{font-size:.88rem;color:#6B7280;line-height:1.65;margin-bottom:2rem}
        .actions{display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap}
        .btn{display:inline-flex;align-items:center;gap:.45rem;padding:.6rem 1.25rem;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:.84rem;font-weight:600;text-decoration:none;transition:all .18s}
        .btn-primary{background:#1A56DB;color:#fff;box-shadow:0 4px 12px rgba(26,86,219,.3)}
        .btn-primary:hover{background:#1347C8;transform:translateY(-1px)}
        .btn-ghost{background:#fff;color:#374151;border:1.5px solid #E5E7EB}
        .btn-ghost:hover{background:#F9FAFB}
    </style>
</head>
<body>
<div class="wrap">
    <div class="code">500</div>
    <div class="icon-ring"><i class="bi bi-exclamation-triangle-fill"></i></div>
    <h1>Something went wrong</h1>
    <p>We're experiencing technical difficulties. Our team has been notified and is working to resolve the issue. Please try again in a few moments.</p>
    <div class="actions">
        <a href="{{ url('/') }}" class="btn btn-primary"><i class="bi bi-house-fill"></i> Go Home</a>
        <button onclick="location.reload()" class="btn btn-ghost"><i class="bi bi-arrow-clockwise"></i> Try Again</button>
    </div>
</div>
</body>
</html>
