<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wildlife Kingdom Admin Login</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Outfit', sans-serif; background: linear-gradient(135deg, #113e2f, #e8a15b); min-height: 100vh; display:flex; align-items:center; justify-content:center; }
        .login-box { width: min(460px, 92vw); background: rgba(255,255,255,.96); border-radius: 20px; padding: 32px; box-shadow: 0 24px 60px rgba(0,0,0,.18); }
        h1 { margin-top: 0; color:#0B2F22; }
        .field { margin-bottom: 18px; }
        label { display:block; font-weight:600; margin-bottom:8px; }
        input { width:100%; padding:12px 14px; border:1px solid #d9d1c2; border-radius: 10px; }
        button { width:100%; background:#0B2F22; color:#fff; padding: 12px 16px; border:0; border-radius: 999px; font-weight:700; cursor:pointer; }
        .note { margin-top: 14px; color:#56514d; font-size: .95rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Wildlife Kingdom Admin</h1>

        @if($errors->any())
            <div style="background:#fff0f0; color:#7f1d1d; border:1px solid #f3c0c0; padding:10px 12px; border-radius:10px; margin-bottom:16px;">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.authenticate') }}">
            @csrf
            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            </div>
            <div class="field">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
            </div>
            <button type="submit">Login to Dashboard</button>
        </form>
    </div>
</body>
</html>
