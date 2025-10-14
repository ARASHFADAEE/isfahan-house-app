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
                    <span class="badge text-primary-dark bg-primary-300 badge-notification ms-2">
                        2
                    </span>
                </a>
                <ul class="collapse" id="dashboard">
                    <li><a href="/dashboard">نمای کلی</a></li>
                    <li><a href="/reports">گزارش‌ها</a></li>
                </ul>
            </li>
            <li class="menu-title">
                <span>مدیریت</span>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#branches">
                    <i class="iconoir-building"></i>
                    شعبه‌ها
                </a>
                <ul class="collapse" id="branches">
                    <li><a href="/branches">لیست شعبه‌ها</a></li>
                    <li><a href="/branches/create">افزودن شعبه</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#users">
                    <i class="iconoir-user"></i>
                    کاربران
                </a>
                <ul class="collapse" id="users">
                    <li><a href="/users">لیست کاربران</a></li>
                    <li><a href="/users/create">افزودن کاربر</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#subscriptions">
                    <i class="iconoir-ticket"></i>
                    اشتراک‌ها
                </a>
                <ul class="collapse" id="subscriptions">
                    <li><a href="/subscriptions">لیست اشتراک‌ها</a></li>
                    <li><a href="/subscriptions/create">افزودن اشتراک</a></li>
                    <li><a href="/subscription-types">انواع اشتراک</a></li>
                    <li><a href="/subscription-types/create">افزودن نوع اشتراک</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#desks">
                    <i class="iconoir-desk"></i>
                    میزها
                </a>
                <ul class="collapse" id="desks">
                    <li><a href="/desks">لیست میزها</a></li>
                    <li><a href="/desks/create">افزودن میز</a></li>
                    <li><a href="/flexible-desk-reservations">رزرو میزهای منعطف</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#lockers">
                    <i class="iconoir-lock"></i>
                    کمدها
                </a>
                <ul class="collapse" id="lockers">
                    <li><a href="/lockers">لیست کمدها</a></li>
                    <li><a href="/lockers/create">افزودن کمد</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#rooms">
                    <i class="iconoir-home"></i>
                    اتاق‌ها
                </a>
                <ul class="collapse" id="rooms">
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
                <ul class="collapse" id="transactions">
                    <li><a href="/transactions">لیست تراکنش‌ها</a></li>
                    <li><a href="/transactions/create">افزودن تراکنش</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#discounts">
                    <i class="iconoir-percentage-circle"></i>
                    تخفیف‌ها
                </a>
                <ul class="collapse" id="discounts">
                    <li><a href="/discounts">لیست تخفیف‌ها</a></li>
                    <li><a href="/discounts/create">افزودن تخفیف</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#notifications">
                    <i class="iconoir-bell"></i>
                    اعلان‌ها
                </a>
                <ul class="collapse" id="notifications">
                    <li><a href="/notifications">لیست اعلان‌ها</a></li>
                    <li><a href="/notifications/create">ارسال اعلان</a></li>
                </ul>
            </li>
            <li>
                <a aria-expanded="false" class="" data-bs-toggle="collapse" href="#permissions">
                    <i class="iconoir-shield-check"></i>
                    مجوزها
                </a>
                <ul class="collapse" id="permissions">
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
