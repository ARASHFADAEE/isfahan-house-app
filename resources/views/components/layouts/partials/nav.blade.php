<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="/dashboard">
            <img alt="لوگو" src="{{ asset('panel/assets/images/logo/1.png') }}" />
        </a>
        <span class="bg-light-primary toggle-semi-nav">
            <i class="ti ti-chevrons-right f-s-20"></i>
        </span>
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2 pb-xl-4">
            <li class="menu-title">
                <span>داشبورد</span>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#dashboard">
                    <i class="iconoir-home-alt"></i>
                    داشبورد

                </a>
                <ul class="collapse" id="dashboard">
                    <li><a href="{{ route('admin.dashboard.index') }}">نمای کلی</a></li>
                    <li><a href="/reports">گزارش‌ها</a></li>
                </ul>
            </li>
            <li class="menu-title">
                <span>مدیریت</span>
            </li>
            <li>
                <a aria-expanded="{{ request()->routeIs('admin.branches.*') ? 'true' : 'false' }}" class="" data-bs-toggle="collapse" href="#branches">
                    <i class="iconoir-building"></i>
                    شعبه‌ها
                </a>
                <ul class="collapse {{ request()->routeIs('admin.branches.*') ? 'show' : '' }}" id="branches" data-bs-parent="#app-simple-bar">
                    <li><a href="{{ route('admin.branches.index') }}">لیست شعبه‌ها</a></li>
                    <li><a href="{{ route('admin.branches.create') }}">افزودن شعبه</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}" class="" data-bs-toggle="collapse" href="#users">
                    <i class="iconoir-user"></i>
                    کاربران
                </a>
                <ul class="collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="users" data-bs-parent="#app-simple-bar">
                    <li><a href="{{ route('admin.users.index') }}">لیست کاربران</a></li>
                    <li><a href="{{ route('admin.users.create') }}">افزودن کاربر</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="{{ request()->routeIs('admin.subscriptions.*') ? 'true' : 'false' }}" class="" data-bs-toggle="collapse" href="#subscriptions">
                    <i class="iconoir-ticket"></i>
                    اشتراک‌ها
                </a>
                <ul class="collapse {{ request()->routeIs('admin.subscriptions.*') ? 'show' : '' }}" id="subscriptions" data-bs-parent="#app-simple-bar">
                    <li><a href="{{ route('admin.subscriptions.index') }}">لیست اشتراک‌ها</a></li>
                    <li><a href="{{ route('admin.subscriptions.create') }}">افزودن اشتراک</a></li>
                    <li><a href="{{ route('admin.subscription_types.index') }}">انواع اشتراک</a></li>
                    <li><a href="{{ route('admin.subscription_types.create') }}">افزودن نوع اشتراک</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="{{ request()->routeIs('admin.desks.*') ? 'true' : 'false' }}" class="" data-bs-toggle="collapse" href="#desks">
                    <i class="iconoir-desk"></i>
                    میزها
                </a>
                <ul class="collapse {{ request()->routeIs('admin.desks.*') ? 'show' : '' }}" id="desks" data-bs-parent="#app-simple-bar">
                    <li><a href="{{ route('admin.desks.index') }}">لیست میزها</a></li>
                    <li><a href="{{ route('admin.desks.create') }}">افزودن میز</a></li>
                    <li><a href="/flexible-desk-reservations">رزرو میزهای منعطف</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#lockers">
                    <i class="iconoir-lock"></i>
                    کمدها
                </a>
                <ul class="collapse" id="lockers" data-bs-parent="#app-simple-bar">
                    <li><a href="/lockers">لیست کمدها</a></li>
                    <li><a href="/lockers/create">افزودن کمد</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#rooms">
                    <i class="iconoir-home"></i>
                    اتاق‌ها
                </a>
                <ul class="collapse" id="rooms" data-bs-parent="#app-simple-bar">
                    <li><a href="/meeting-rooms">لیست اتاق‌های جلسات</a></li>
                    <li><a href="/meeting-rooms/create">افزودن اتاق جلسه</a></li>
                    <li><a href="/meeting-reservations">رزرو اتاق‌های جلسات</a></li>
                    <li><a href="/private-rooms">لیست اتاق‌های اختصاصی</a></li>
                    <li><a href="/private-rooms/create">افزودن اتاق اختصاصی</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#transactions">
                    <i class="iconoir-credit-card"></i>
                    تراکنش‌ها
                </a>
                <ul class="collapse" id="transactions" data-bs-parent="#app-simple-bar">
                    <li><a href="/transactions">لیست تراکنش‌ها</a></li>
                    <li><a href="/transactions/create">افزودن تراکنش</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#discounts">
                    <i class="iconoir-percentage-circle"></i>
                    تخفیف‌ها
                </a>
                <ul class="collapse" id="discounts" data-bs-parent="#app-simple-bar">
                    <li><a href="/discounts">لیست تخفیف‌ها</a></li>
                    <li><a href="/discounts/create">افزودن تخفیف</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#notifications">
                    <i class="iconoir-bell"></i>
                    اعلان‌ها
                </a>
                <ul class="collapse" id="notifications" data-bs-parent="#app-simple-bar">
                    <li><a href="/notifications">لیست اعلان‌ها</a></li>
                    <li><a href="/notifications/create">ارسال اعلان</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#permissions">
                    <i class="iconoir-shield-check"></i>
                    مجوزها
                </a>
                <ul class="collapse" id="permissions" data-bs-parent="#app-simple-bar">
                    <li><a href="/permissions">لیست مجوزها</a></li>
                    <li><a href="/permissions/create">افزودن مجوز</a></li>
                </ul>
            </li>
            <li class="no-sub">
                <a class="" href="/settings">
                    <i class="iconoir-settings"></i>
                    تنظیمات
                </a>
            </li>
        </ul>
    </div>
    <div class="menu-navs">
        <span class="menu-previous"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next"><i class="ti ti-chevron-right"></i></span>
    </div>
</nav>
