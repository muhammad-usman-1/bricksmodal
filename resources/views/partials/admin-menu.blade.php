<!-- Mobile Burger Menu -->
<div class="block lg:hidden px-4 py-2">
    <div class="flex justify-between items-center">
        <img src="/images/logo.png" class="w-24" alt="BRICKS Model" />
        <button id="burgerBtn" class="text-black focus:outline-none">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>
    <!-- Slide-in menu -->
    <div id="mobileMenu"
        class="fixed top-0 left-0 h-full w-64 bg-white shadow-lg z-50 transform -translate-x-full transition-transform duration-300 flex flex-col gap-2 pt-8 px-4">
        <button id="closeMenu" class="absolute top-4 right-4 text-black">
            <svg width="24" height="24" fill="none" viewBox="0 0 24 24">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <a href="{{ route('admin.home') }}" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg width="24" height="24" fill="none">
                <path
                    d="M3.99999 10L12 3L20 10L20 20H15V16C15 15.2044 14.6839 14.4413 14.1213 13.8787C13.5587 13.3161 12.7956 13 12 13C11.2043 13 10.4413 13.3161 9.87868 13.8787C9.31607 14.4413 9 15.2043 9 16V20H4L3.99999 10Z"
                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>{{ trans('global.admin_dashboard') }}</span>
        </a>
        <a href="#" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg fill="#000000" version="1.1" id="Capa_1" width="24px" height="24px"
                viewBox="0 0 452.392 452.393" xml:space="preserve">
                <g>
                    <g id="Layer_8_23_">
                        <path d="M406.657,149.964H122.883l271.078-78.849c5.842-1.699,9.224-7.841,7.517-13.686L387.092,7.957
c-1.694-5.844-7.834-9.215-13.68-7.518L42.666,96.647c-5.844,1.699-9.217,7.839-7.52,13.685l14.39,49.47
c0.353,1.212,0.936,2.293,1.632,3.267v49.456c0,6.086,4.951,11.04,11.042,11.04h7.356v219.999c0,4.879,3.962,8.829,8.832,8.829
h312.062c4.87,0,8.83-3.955,8.83-8.829V223.565h7.354c6.085,0,11.043-4.954,11.043-11.04v-51.521
C417.699,154.916,412.741,149.964,406.657,149.964z M259.985,154.38h35.337l-35.224,64.769h-35.33L259.985,154.38z
 M164.308,154.38h35.333l-35.226,64.769h-35.331L164.308,154.38z M321.548,20.125l51.907,52.35l-33.913,9.87l-51.92-52.354
L321.548,20.125z M233.915,45.613l51.915,52.353l-33.923,9.866l-51.924-52.351L233.915,45.613z M47.035,135.395l-7.647-26.296
c-1.021-3.508,1.008-7.19,4.51-8.208l9.805-2.848l51.489,51.927H62.21c-0.225,0-0.439,0.057-0.655,0.068L47.035,135.395z
 M91.889,154.38l-20.194,5.877l-1.665-1.674l2.282-4.203H91.889z M72.419,219.149H62.214c-3.653,0-6.629-2.97-6.629-6.624v-27.39
l10.083-18.528l41.961-12.21L72.419,219.149z M142.034,72.341l51.916,52.351l-33.923,9.869L108.11,82.207L142.034,72.341z
 M356.75,347.402H110.937v-14.244H356.75V347.402z M356.75,287.045H110.937v-14.237H356.75V287.045z M351.36,219.149h-35.331
l35.216-64.769h35.339L351.36,219.149z" />
                    </g>
                </g>
            </svg>
            <h1 class="text-xl text-black">Project</h1>
        </a>
        
        <a href="#" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#F1F2F4]">
            <svg width="24" height="24" fill="none">
                <path
                    d="M11 8.5C11 9.88071 9.88071 11 8.5 11C7.11929 11 6 9.88071 6 8.5C6 7.11929 7.11929 6 8.5 6C9.88071 6 11 7.11929 11 8.5Z"
                    stroke="#000000" stroke-width="2" />
                <path
                    d="M18 5.5C18 6.88071 16.8807 8 15.5 8C14.1193 8 13 6.88071 13 5.5C13 4.11929 14.1193 3 15.5 3C16.8807 3 18 4.11929 18 5.5Z"
                    stroke="#000000" stroke-width="2" />
                <path
                    d="M15.5 20C14.5 21 3.00002 20.5 2.00001 20C1 19.5 5.41016 15 9.00001 15C12.5899 15 16.076 19.424 15.5 20Z"
                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Talent</span>
        </a>
        <a href="#" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg width="24" height="24" fill="none">
                <circle cx="12" cy="12" r="10" stroke="#000000" stroke-width="1.5" />
                <path d="M12 6V18" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
                <path
                    d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <span>Payments</span>
        </a>
        @if(auth('admin')->check() && auth('admin')->user()->isSuperAdmin())
        <a href="{{ route('admin.payment-requests.index') }}" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg width="24" height="24" fill="none">
                <path d="M12 2C13.1 2 14 2.9 14 4V5H16C17.1 5 18 5.9 18 7V10H20C21.1 10 22 10.9 22 12V20C22 21.1 21.1 22 20 22H12C10.9 22 10 21.1 10 20V12C10 10.9 10.9 10 12 10H14V7H12C11.4 7 11 6.6 11 6V4C11 3.4 11.4 3 12 3V2ZM12 12H10V20H12V12Z" stroke="#000000" stroke-width="1.5"/>
            </svg>
            <span>Payment Requests</span>
        </a>
        <a href="{{ route('admin.admin-management.index') }}" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg width="24" height="24" fill="none">
                <path d="M12 2L3 7V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V7L12 2Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>Admin Management</span>
        </a>
        @endif
        <!-- @can('talent_profile_access')
        <a href="{{ route('admin.talent-profiles.index') }}" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg width="24" height="24" fill="none">
                <path d="M12 2V6" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M12 18V22" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M4.93 4.93L7.76 7.76" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M16.24 16.24L19.07 19.07" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M2 12H6" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M18 12H22" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M4.93 19.07L7.76 16.24" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M16.24 7.76L19.07 4.93" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="12" r="3" stroke="#000000" stroke-width="1.5"/>
            </svg>
            <span>Talent Profiles</span>
        </a>
        @endcan -->
        @if(auth('admin')->check() && auth('admin')->user()->isSuperAdmin())
        <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-[#EBF0FA]">
            <svg width="24" height="24" fill="none">
                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#000000" stroke-width="1.5"/><path d="M2.45801 12.5C2.73128 13.7625 3.31494 14.9445 4.16104 15.9426C5.00714 16.9407 6.09143 17.7257 7.31301 18.2275L8.31301 16.2275C7.51601 15.8875 6.80714 15.3775 6.22801 14.74C5.64888 14.1025 5.21314 13.3575 4.95801 12.5425L2.45801 12.5ZM21.542 12.5C21.2687 13.7625 20.6851 14.9445 19.839 15.9426C18.9929 16.9407 17.9086 17.7257 16.687 18.2275L15.687 16.2275C16.484 15.8875 17.1929 15.3775 17.772 14.74C18.3511 14.1025 18.7869 13.3575 19.042 12.5425L21.542 12.5ZM12.5 2.45801C13.7625 2.73128 14.9445 3.31494 15.9426 4.16104C16.9407 5.00714 17.7257 6.09143 18.2275 7.31301L16.2275 8.31301C15.8875 7.51601 15.3775 6.80714 14.74 6.22801C14.1025 5.64888 13.3575 5.21314 12.5425 4.95801L12.5 2.45801ZM12.5 21.542C13.7625 21.2687 14.9445 20.6851 15.9426 19.839C16.9407 18.9929 17.7257 17.9086 18.2275 16.687L16.2275 15.687C15.8875 16.484 15.3775 17.1929 14.74 17.772C14.1025 18.3511 13.3575 18.7869 12.5425 19.042L12.5 21.542ZM8.31301 16.2275L7.31301 18.2275C6.09143 17.7257 5.00714 16.9407 4.16104 15.9426C3.31494 14.9445 2.73128 13.7625 2.45801 12.5L4.95801 12.5425C5.21314 13.3575 5.64888 14.1025 6.22801 14.74C6.80714 15.3775 7.51601 15.8875 8.31301 16.2275ZM15.687 16.2275L16.687 18.2275C17.9086 17.7257 18.9929 16.9407 19.839 15.9426C20.6851 14.9445 21.2687 13.7625 21.542 12.5L19.042 12.5425C18.7869 13.3575 18.3511 14.1025 17.772 14.74C17.1929 15.3775 16.484 15.8875 15.687 16.2275ZM16.2275 8.31301L18.2275 7.31301C17.7257 6.09143 16.9407 5.00714 15.9426 4.16104C14.9445 3.31494 13.7625 2.73128 12.5 2.45801L12.5425 4.95801C13.3575 5.21314 14.1025 5.64888 14.74 6.22801C15.3775 6.80714 15.8875 7.51601 16.2275 8.31301ZM16.2275 15.687L18.2275 16.687C17.7257 17.9086 16.9407 18.9929 15.9426 19.839C14.9445 20.6851 13.7625 21.2687 12.5 21.542L12.5425 19.042C13.3575 18.7869 14.1025 18.3511 14.74 17.772C15.3775 17.1929 15.8875 16.484 16.2275 15.687Z" stroke="#000000" stroke-width="1.5"/>
            </svg>
            <span>Settings</span>
        </a>
        @endif
        <a href="#" class="flex items-center gap-2 py-2 px-3 rounded hover:bg-red-50 text-red-600">
            <svg width="24" height="24" fill="none">
                <path
                    d="M15 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H15M8 8L4 12M4 12L8 16M4 12L16 12"
                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>Logout</span>
        </a>
    </div>
    <script>
        const burgerBtn = document.getElementById('burgerBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        const closeMenu = document.getElementById('closeMenu');
        burgerBtn.addEventListener('click', function () {
            mobileMenu.classList.remove('-translate-x-full');
        });
        closeMenu.addEventListener('click', function () {
            mobileMenu.classList.add('-translate-x-full');
        });
    </script>
</div>
<!-- Sidebar - Hidden on mobile -->
<div class="hidden lg:block lg:col-span-2">
    <div class="flex-shrink-0 ml-10 mb-10 mt-2">
        <img src="/images/logo.png" class="w-36 md:w-44" alt="BRICKS Model" />
    </div>

    <!-- Dashboard -->
    <a href="{{ route('admin.home') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }} ">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path
                    d="M3.99999 10L12 3L20 10L20 20H15V16C15 15.2044 14.6839 14.4413 14.1213 13.8787C13.5587 13.3161 12.7956 13 12 13C11.2043 13 10.4413 13.3161 9.87868 13.8787C9.31607 14.4413 9 15.2043 9 16V20H4L3.99999 10Z"
                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h1 class="text-xl text-black">Dashboard</h1>
        </div>
    </a>

    <!-- Project -->
    <a href="{{ route('admin.projects.dashboard') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/projects') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }} ">
            <svg fill="#000000" version="1.1" id="Capa_1" width="30px" height="30px"
                viewBox="0 0 452.392 452.393" xml:space="preserve">
                <g>
                    <g id="Layer_8_23_">
                        <path d="M406.657,149.964H122.883l271.078-78.849c5.842-1.699,9.224-7.841,7.517-13.686L387.092,7.957
    c-1.694-5.844-7.834-9.215-13.68-7.518L42.666,96.647c-5.844,1.699-9.217,7.839-7.52,13.685l14.39,49.47
    c0.353,1.212,0.936,2.293,1.632,3.267v49.456c0,6.086,4.951,11.04,11.042,11.04h7.356v219.999c0,4.879,3.962,8.829,8.832,8.829
    h312.062c4.87,0,8.83-3.955,8.83-8.829V223.565h7.354c6.085,0,11.043-4.954,11.043-11.04v-51.521
    C417.699,154.916,412.741,149.964,406.657,149.964z M259.985,154.38h35.337l-35.224,64.769h-35.33L259.985,154.38z
    M164.308,154.38h35.333l-35.226,64.769h-35.331L164.308,154.38z M321.548,20.125l51.907,52.35l-33.913,9.87l-51.92-52.354
    L321.548,20.125z M233.915,45.613l51.915,52.353l-33.923,9.866l-51.924-52.351L233.915,45.613z M47.035,135.395l-7.647-26.296
    c-1.021-3.508,1.008-7.19,4.51-8.208l9.805-2.848l51.489,51.927H62.21c-0.225,0-0.439,0.057-0.655,0.068L47.035,135.395z
    M91.889,154.38l-20.194,5.877l-1.665-1.674l2.282-4.203H91.889z M72.419,219.149H62.214c-3.653,0-6.629-2.97-6.629-6.624v-27.39
    l10.083-18.528l41.961-12.21L72.419,219.149z M142.034,72.341l51.916,52.351l-33.923,9.869L108.11,82.207L142.034,72.341z
    M356.75,347.402H110.937v-14.244H356.75V347.402z M356.75,287.045H110.937v-14.237H356.75V287.045z M351.36,219.149h-35.331
    l35.216-64.769h35.339L351.36,219.149z" />
                    </g>
                </g>
            </svg>
            <h1 class="text-xl text-black">Project</h1>
        </div>
    </a>

    <!-- talent -->
    <a href="{{ route('admin.talents.dashboard') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/talents') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }}">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11 8.5C11 9.88071 9.88071 11 8.5 11C7.11929 11 6 9.88071 6 8.5C6 7.11929 7.11929 6 8.5 6C9.88071 6 11 7.11929 11 8.5Z"
                    stroke="#000000" stroke-width="2" />
                <path
                    d="M18 5.5C18 6.88071 16.8807 8 15.5 8C14.1193 8 13 6.88071 13 5.5C13 4.11929 14.1193 3 15.5 3C16.8807 3 18 4.11929 18 5.5Z"
                    stroke="#000000" stroke-width="2" />
                <path
                    d="M15.5 20C14.5 21 3.00002 20.5 2.00001 20C1 19.5 5.41016 15 9.00001 15C12.5899 15 16.076 19.424 15.5 20Z"
                    stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h1 class="text-xl text-black">Talent</h1>
        </div>
    </a>
    <!-- Payments -->
    <a href="{{ route('admin.payments.dashboard') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/payments') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }}">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="12" r="10" stroke="#000000" stroke-width="1.5" />
                <path d="M12 6V18" stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
                <path
                    d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5"
                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <h1 class="text-xl text-black">Payments</h1>
        </div>
    </a>

    @if(auth('admin')->check() && auth('admin')->user()->isSuperAdmin())
    <!-- Payment Requests -->
    <a href="{{ route('admin.payment-requests.index') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/payment-requests*') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }} ">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2C13.1 2 14 2.9 14 4V5H16C17.1 5 18 5.9 18 7V10H20C21.1 10 22 10.9 22 12V20C22 21.1 21.1 22 20 22H12C10.9 22 10 21.1 10 20V12C10 10.9 10.9 10 12 10H14V7H12C11.4 7 11 6.6 11 6V4C11 3.4 11.4 3 12 3V2ZM12 12H10V20H12V12Z" stroke="#000000" stroke-width="1.5"/>
            </svg>
            <h1 class="text-xl text-black">Payment Requests</h1>
        </div>
    </a>
    <!-- Admin Management -->
    <a href="{{ route('admin.admin-management.index') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/admin-management*') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }} ">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2L3 7V11C3 16.55 6.84 21.74 12 23C17.16 21.74 21 16.55 21 11V7L12 2Z" stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h1 class="text-xl text-black">Admin Management</h1>
        </div>
    </a>
    @endif
    @can('talent_profile_access')
    <!-- Talent Profiles -->
    <!-- <a href="{{ route('admin.talent-profiles.index') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/talent-profiles*') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }} ">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 2V6" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M12 18V22" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M4.93 4.93L7.76 7.76" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M16.24 16.24L19.07 19.07" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M2 12H6" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M18 12H22" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M4.93 19.07L7.76 16.24" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><path d="M16.24 7.76L19.07 4.93" stroke="#000000" stroke-width="1.5" stroke-linecap="round"/><circle cx="12" cy="12" r="3" stroke="#000000" stroke-width="1.5"/>
            </svg>
            <h1 class="text-xl text-black">Talent Profiles</h1>
        </div> -->
    </a>
    @endcan
    @if(auth('admin')->check() && auth('admin')->user()->isSuperAdmin())
    <!-- Settings -->
    <a href="{{ route('admin.settings.index') }}" >
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer transition-colors {{ request()->is('admin/settings') ? 'bg-[#F1F2F4]' : 'hover:bg-[#EBF0FA]' }} ">
            <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 15C13.6569 15 15 13.6569 15 12C15 10.3431 13.6569 9 12 9C10.3431 9 9 10.3431 9 12C9 13.6569 10.3431 15 12 15Z" stroke="#000000" stroke-width="1.5"/><path d="M2.45801 12.5C2.73128 13.7625 3.31494 14.9445 4.16104 15.9426C5.00714 16.9407 6.09143 17.7257 7.31301 18.2275L8.31301 16.2275C7.51601 15.8875 6.80714 15.3775 6.22801 14.74C5.64888 14.1025 5.21314 13.3575 4.95801 12.5425L2.45801 12.5ZM21.542 12.5C21.2687 13.7625 20.6851 14.9445 19.839 15.9426C18.9929 16.9407 17.9086 17.7257 16.687 18.2275L15.687 16.2275C16.484 15.8875 17.1929 15.3775 17.772 14.74C18.3511 14.1025 18.7869 13.3575 19.042 12.5425L21.542 12.5ZM12.5 2.45801C13.7625 2.73128 14.9445 3.31494 15.9426 4.16104C16.9407 5.00714 17.7257 6.09143 18.2275 7.31301L16.2275 8.31301C15.8875 7.51601 15.3775 6.80714 14.74 6.22801C14.1025 5.64888 13.3575 5.21314 12.5425 4.95801L12.5 2.45801ZM12.5 21.542C13.7625 21.2687 14.9445 20.6851 15.9426 19.839C16.9407 18.9929 17.7257 17.9086 18.2275 16.687L16.2275 15.687C15.8875 16.484 15.3775 17.1929 14.74 17.772C14.1025 18.3511 13.3575 18.7869 12.5425 19.042L12.5 21.542ZM8.31301 16.2275L7.31301 18.2275C6.09143 17.7257 5.00714 16.9407 4.16104 15.9426C3.31494 14.9445 2.73128 13.7625 2.45801 12.5L4.95801 12.5425C5.21314 13.3575 5.64888 14.1025 6.22801 14.74C6.80714 15.3775 7.51601 15.8875 8.31301 16.2275ZM15.687 16.2275L16.687 18.2275C17.9086 17.7257 18.9929 16.9407 19.839 15.9426C20.6851 14.9445 21.2687 13.7625 21.542 12.5L19.042 12.5425C18.7869 13.3575 18.3511 14.1025 17.772 14.74C17.1929 15.3775 16.484 15.8875 15.687 16.2275ZM16.2275 8.31301L18.2275 7.31301C17.7257 6.09143 16.9407 5.00714 15.9426 4.16104C14.9445 3.31494 13.7625 2.73128 12.5 2.45801L12.5425 4.95801C13.3575 5.21314 14.1025 5.64888 14.74 6.22801C15.3775 6.80714 15.8875 7.51601 16.2275 8.31301ZM16.2275 15.687L18.2275 16.687C17.7257 17.9086 16.9407 18.9929 15.9426 19.839C14.9445 20.6851 13.7625 21.2687 12.5 21.542L12.5425 19.042C13.3575 18.7869 14.1025 18.3511 14.74 17.772C15.3775 17.1929 15.8875 16.484 16.2275 15.687Z" stroke="#000000" stroke-width="1.5"/>
            </svg>
            <h1 class="text-xl text-black">Settings</h1>
        </div>
    </a>
    @endif
    <!-- Logout -->
    <a href="{{ route('admin.logout') }}" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
        <div
            class="flex gap-4 mx-8 mb-5 py-2 rounded-lg pl-8 items-center cursor-pointer hover:bg-red-50 text-red-600 transition-colors">
            <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path
                    d="M15 4H18C19.1046 4 20 4.89543 20 6V18C20 19.1046 19.1046 20 18 20H15M8 8L4 12M4 12L8 16M4 12L16 12"
                    stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <h1 class="text-xl">Logout</h1>
        </div>
    </a>

    <form id="logoutform" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
        @csrf
    </form>
</div> 