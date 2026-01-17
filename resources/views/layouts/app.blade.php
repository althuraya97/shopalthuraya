<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>لوحة التحكم - مباهج الخد</title>
    <link rel="icon" type="image/png" href="https://mabahijalkhad.com/wp-content/uploads/2024/09/%D9%85%D8%A8%D8%A7%D9%87%D8%AC-%D8%A7%D9%84%D8%AE%D8%AF.png">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root { --sidebar-width: 260px; }
        body { background-color: #f8f9fa; overflow-x: hidden; }

        /* تنسيق السايدبار المنزلق */
        .sidebar-wrapper {
            width: var(--sidebar-width);
            background-color: #212529;
            height: 100vh;
            position: fixed;
            top: 0;
            right: -260px; /* مخفي افتراضياً في البداية */
            transition: all 0.3s ease;
            z-index: 1050;
            box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        }

        /* عندما يكون السايدبار مفتوحاً */
        .sidebar-open .sidebar-wrapper { right: 0; }

        /* طبقة التظليل خلف السايدبار */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        .sidebar-open .sidebar-overlay { display: block; }

        .main-content-wrapper { padding-top: 70px; transition: all 0.3s; }

        /* الهيدر العلوي */
        .top-navbar {
            height: 60px;
            background: #fff;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .toggle-icon { cursor: pointer; font-size: 24px; color: #212529; transition: 0.2s; }
        .toggle-icon:hover { color: #0d6efd; }
    </style>
</head>
<body>

    <nav class="top-navbar d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fas fa-bars toggle-icon ms-3" id="sidebarToggle"></i>
            <span class="fw-bold h5 mb-0">مباهج الخد <small class="text-muted fs-6">| لوحة التحكم</small></span>
        </div>
        <img src="https://mabahijalkhad.com/wp-content/uploads/2024/09/%D9%85%D8%A8%D8%A7%D9%87%D8%AC-%D8%A7%D9%84%D8%AE%D8%AF.png" width="40">
    </nav>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="admin-layout">
        <aside class="sidebar-wrapper">
            <div class="p-3 border-bottom border-secondary d-flex justify-content-between align-items-center text-white">
                <span>القائمة الرئيسية</span>
                <i class="fas fa-times cursor-pointer" id="closeSidebar"></i>
            </div>
            @include('layouts.admin-sidebar')
        </aside>

        <main class="main-content-wrapper container-fluid">
            @yield('content')
        </main>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        const body = document.body;
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // وظيفة لفتح/إغلاق السايدبار
        function toggleSidebar() {
            body.classList.toggle('sidebar-open');
        }

        // وظيفة لإغلاق السايدبار فقط
        function closeSidebar() {
            body.classList.remove('sidebar-open');
        }

        toggleBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // --- الجزء المطلوب: إخفاء السايدبار عند الضغط على أزرار الفلاتر أو الحفظ ---
        document.addEventListener('click', function(event) {
            // ابحث عن أي زر يحتوي على كلمة "فلتر" أو "حفظ" أو "تطبيق"
            if (event.target.closest('button[type="submit"]') ||
                event.target.innerText.includes('تطبيق') ||
                event.target.innerText.includes('فلتر')) {

                // نقوم بتأخير الإغلاق قليلاً ليشعر المستخدم بالضغط
                setTimeout(closeSidebar, 200);
            }
        });
    </script>
</body>
</html>
