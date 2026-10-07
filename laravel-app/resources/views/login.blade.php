<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>تسجيل الدخول</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #222748;
      --accent-color: #d8901d;
      --white: #ffffff;
      --bg-light: #f4f4f9;
    }

    body {
      background-color: var(--bg-light);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      margin: 0;
    }

    .login-wrapper {
      width: 95%;
      max-width: 900px;
      min-height: 500px;
      background: var(--white);
      display: flex;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
      border-radius: 15px;
      overflow: hidden;
      border: 2px solid var(--primary-color);
    }

    /* اللوحة الكحلية (نفس لوحة المعلومات في الخريطة) */
    .brand-panel {
      flex: 0 0 350px;
      background-color: var(--primary-color);
      color: var(--white);
      padding: 35px 25px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      border-left: 4px solid var(--accent-color);
    }

    .brand-panel h2 {
      color: var(--accent-color);
      border-bottom: 2px solid rgba(216, 144, 29, 0.3);
      padding-bottom: 10px;
      font-size: 24px;
      margin-bottom: 15px;
    }

    .brand-panel p {
      font-size: 14px;
      opacity: 0.8;
      line-height: 1.9;
      margin: 0;
    }

    .lock-icon {
      width: 64px;
      height: 64px;
      border: 2px solid var(--accent-color);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 20px;
      background: rgba(255, 255, 255, 0.08);
    }

    .form-panel {
      flex: 1;
      padding: 40px 45px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-panel h3 {
      color: var(--primary-color);
      font-weight: bold;
      margin-bottom: 6px;
    }

    .form-panel .sub {
      color: #777;
      font-size: 14px;
      margin-bottom: 25px;
    }

    .form-label {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 14px;
    }

    .form-control {
      padding: 10px 14px;
      border-radius: 8px;
      border: 1px solid #ccd;
    }

    .form-control:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 3px rgba(216, 144, 29, 0.2);
    }

    .pass-group {
      position: relative;
    }

    .toggle-pass {
      position: absolute;
      left: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--primary-color);
      font-size: 12px;
      cursor: pointer;
      padding: 4px 6px;
    }

    .toggle-pass:hover {
      color: var(--accent-color);
    }

    .form-check-input:checked {
      background-color: var(--primary-color);
      border-color: var(--primary-color);
    }

    .login-btn {
      width: 100%;
      background: var(--accent-color);
      color: var(--white);
      border: none;
      padding: 11px;
      border-radius: 8px;
      font-weight: bold;
      font-size: 15px;
      cursor: pointer;
      transition: 0.3s;
    }

    .login-btn:hover {
      filter: brightness(1.15);
    }

    .login-btn:focus-visible,
    .toggle-pass:focus-visible {
      outline: 3px solid var(--primary-color);
      outline-offset: 2px;
    }

    .error-msg {
      display: none;
      background: #fdecec;
      color: #b42318;
      border: 1px solid #f5c2c0;
      border-radius: 8px;
      padding: 8px 12px;
      font-size: 13px;
      margin-bottom: 15px;
    }

    .forgot {
      color: var(--primary-color);
      font-size: 13px;
      text-decoration: none;
    }

    .forgot:hover {
      color: var(--accent-color);
    }

    @media (max-width: 768px) {
      .login-wrapper {
        flex-direction: column;
      }

      .brand-panel {
        flex: none;
        border-left: none;
        border-bottom: 4px solid var(--accent-color);
        padding: 25px;
      }

      .form-panel {
        padding: 30px 25px;
      }
    }
  </style>
</head>

<body>
  <div class="login-wrapper">
    <div class="brand-panel">
      <div class="lock-icon">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#d8901d" stroke-width="2"
          stroke-linecap="round" stroke-linejoin="round">
          <rect x="4" y="11" width="16" height="10" rx="2"></rect>
          <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
        </svg>
      </div>
      <h2>مرحباً بك</h2>
      <p>سجّل دخولك للاطلاع على بيانات المناطق ومؤشرات الجاهزية.</p>
    </div>

    <div class="form-panel">
      <h3>تسجيل الدخول</h3>
      <div class="sub">أدخل بيانات حسابك للمتابعة.</div>

      <div id="errorMsg" class="error-msg" role="alert"></div>

      <form id="loginForm" novalidate>
        <div class="mb-3">
          <label for="username" class="form-label">اسم المستخدم</label>
          <input type="text" id="username" class="form-control" autocomplete="username" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">كلمة المرور</label>
          <div class="pass-group">
            <input type="password" id="password" class="form-control" autocomplete="current-password" required>
            <button type="button" id="togglePass" class="toggle-pass">إظهار</button>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember">
            <label class="form-check-label" for="remember" style="font-size:13px;">تذكرني</label>
          </div>
          <a href="#" class="forgot">نسيت كلمة المرور؟</a>
        </div>

        <button type="submit" class="login-btn">دخول</button>
      </form>
    </div>
  </div>

  <script>
    const form = document.getElementById('loginForm');
    const errorMsg = document.getElementById('errorMsg');
    const passInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePass');

    toggleBtn.addEventListener('click', () => {
      const show = passInput.type === 'password';
      passInput.type = show ? 'text' : 'password';
      toggleBtn.textContent = show ? 'إخفاء' : 'إظهار';
    });

    function showError(text) {
      errorMsg.textContent = text;
      errorMsg.style.display = 'block';
    }

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      errorMsg.style.display = 'none';

      const username = document.getElementById('username').value.trim();
      const password = passInput.value;

      if (!username || !password) {
        showError('فضلاً أدخل اسم المستخدم وكلمة المرور.');
        return;
      }

      // TODO: اربطها بالسيرفر عندكم (غيّر الرابط حسب نظامكم)
      try {
        const res = await fetch('/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            username,
            password,
            remember: document.getElementById('remember').checked
          })
        });
        if (!res.ok) {
          showError('اسم المستخدم أو كلمة المرور غير صحيحة.');
          return;
        }
        window.location.href = 'index.html'; // صفحة الخريطة
      } catch (err) {
        showError('تعذّر الاتصال بالخادم. حاول مرة أخرى.');
      }
    });
  </script>
</body>

</html>
