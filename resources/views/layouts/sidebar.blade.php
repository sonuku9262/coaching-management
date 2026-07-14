@php
    $menuSections = [
        [
            'id' => 'menuUsers',
            'icon' => '👥',
            'label' => 'User Management',
            'links' => [
                ['label' => 'Roles', 'url' => '/admin/roles', 'pattern' => 'admin/roles*', 'permission' => 'roles.view'],
                ['label' => 'Users', 'url' => '/admin/users', 'pattern' => 'admin/users*', 'permission' => 'users.view'],
                ['label' => 'Permissions', 'url' => '/admin/permissions', 'pattern' => 'admin/permissions*', 'permission' => 'permissions.view'],
            ],
        ],
        [
            'id' => 'menuTimetable',
            'icon' => '🗓️',
            'label' => 'Timetable',
            'links' => [
                ['label' => 'Class Timetable', 'url' => '/admin/timetable', 'pattern' => 'admin/timetable*', 'permission' => 'timetables.view'],
            ],
        ],
        [
            'id' => 'menuAcademics',
            'icon' => '📚',
            'label' => 'Academics',
            'links' => [
                ['label' => 'Study Material', 'url' => '/admin/study-material', 'pattern' => 'admin/study-material*', 'permission' => 'study-materials.view'],
                ['label' => 'Homework', 'url' => '/admin/homework', 'pattern' => 'admin/homework*', 'permission' => 'homework.view'],
            ],
        ],
        [
            'id' => 'menuMaster',
            'icon' => '🗂️',
            'label' => 'Master Data',
            'links' => [
                ['label' => 'Academic Year', 'url' => '/admin/academic-years', 'pattern' => 'admin/academic-years*', 'permission' => 'academic-years.view'],
                ['label' => 'Academic Session', 'url' => '/admin/academic-sessions', 'pattern' => 'admin/academic-sessions*', 'permission' => 'academic-sessions.view'],
                ['label' => 'Courses', 'url' => '/admin/courses', 'pattern' => 'admin/courses*', 'permission' => 'courses.view'],
                ['label' => 'Subjects', 'url' => '/admin/subjects', 'pattern' => 'admin/subjects*', 'permission' => 'subjects.view'],
                ['label' => 'Batches', 'url' => '/admin/batches', 'pattern' => 'admin/batches*', 'permission' => 'batches.view'],
                ['label' => 'Classrooms', 'url' => '/admin/classrooms', 'pattern' => 'admin/classrooms*', 'permission' => 'classrooms.view'],
                ['label' => 'Shifts', 'url' => '/admin/shifts', 'pattern' => 'admin/shifts*', 'permission' => 'shifts.view'],
            ],
        ],
        [
            'id' => 'menuStudents',
            'icon' => '🎓',
            'label' => 'Student Management',
            'links' => [
                ['label' => 'Student Registration', 'url' => '/admin/student-registrations', 'pattern' => 'admin/student-registrations*', 'permission' => 'students.view'],
                ['label' => 'Enquiries', 'url' => '/admin/enquiries', 'pattern' => 'admin/enquiries*', 'permission' => 'enquiries.view'],
                ['label' => 'ID Cards', 'url' => '/admin/id-cards', 'pattern' => 'admin/id-cards*', 'permission' => 'students.view'],
            ],
        ],
        [
            'id' => 'menuTeachers',
            'icon' => '👨‍🏫',
            'label' => 'Teacher Management',
            'links' => [
                ['label' => 'Teachers', 'url' => '/admin/teachers', 'pattern' => 'admin/teachers*', 'permission' => 'teachers.view'],
            ],
        ],
        [
            'id' => 'menuFees',
            'icon' => '💰',
            'label' => 'Fee Management',
            'links' => [
                ['label' => 'Fee Type', 'url' => '/admin/fee-types', 'pattern' => 'admin/fee-types*', 'permission' => 'fee-types.view'],
                ['label' => 'Fee Structure', 'url' => '/admin/fee-structures', 'pattern' => 'admin/fee-structures*', 'permission' => 'fee-structures.view'],
                ['label' => 'Fee Collection', 'url' => '/admin/fee-collections', 'pattern' => 'admin/fee-collections*', 'permission' => 'fee-collections.view'],
            ],
        ],
        [
            'id' => 'menuExpenses',
            'icon' => '🧾',
            'label' => 'Expense Management',
            'links' => [
                ['label' => 'Expense Category', 'url' => '/admin/expense-categories', 'pattern' => 'admin/expense-categories*', 'permission' => 'expense-categories.view'],
                ['label' => 'Expenses', 'url' => '/admin/expenses', 'pattern' => 'admin/expenses*', 'permission' => 'expenses.view'],
                ['label' => 'Salary Payments', 'url' => '/admin/salary-payments', 'pattern' => 'admin/salary-payments*', 'permission' => 'salary-payments.view'],
            ],
        ],
        [
            'id' => 'menuAttendance',
            'icon' => '📋',
            'label' => 'Attendance',
            'links' => [
                ['label' => 'Student Attendance', 'url' => '/admin/student-attendance', 'pattern' => 'admin/student-attendance*', 'permission' => 'student-attendance.view'],
                ['label' => 'Teacher Attendance', 'url' => '/admin/teacher-attendance', 'pattern' => 'admin/teacher-attendance*', 'permission' => 'teacher-attendance.view'],
            ],
        ],
        [
            'id' => 'menuExams',
            'icon' => '📝',
            'label' => 'Examination',
            'links' => [
                ['label' => 'Exams', 'url' => '/admin/exams', 'pattern' => 'admin/exams*', 'permission' => 'exams.view'],
                ['label' => 'Marks Entry', 'url' => '/admin/exam-results', 'pattern' => 'admin/exam-results*', 'permission' => 'exam-results.view'],
                ['label' => 'Report Card', 'url' => '/admin/exam-report-card', 'pattern' => 'admin/exam-report-card*', 'permission' => 'exam-results.view'],
                ['label' => 'Admit Card', 'url' => '/admin/admit-cards', 'pattern' => 'admin/admit-cards*', 'permission' => 'exams.view'],
            ],
        ],
        [
            'id' => 'menuReports',
            'icon' => '📊',
            'label' => 'Reports',
            'links' => [
                ['label' => 'Fee Collection', 'url' => '/admin/reports/fees', 'pattern' => 'admin/reports/fees*', 'permission' => 'reports.view'],
                ['label' => 'Attendance', 'url' => '/admin/reports/attendance', 'pattern' => 'admin/reports/attendance*', 'permission' => 'reports.view'],
                ['label' => 'Fee Dues', 'url' => '/admin/reports/dues', 'pattern' => 'admin/reports/dues*', 'permission' => 'reports.view'],
                ['label' => 'Profit & Loss', 'url' => '/admin/reports/profit-loss', 'pattern' => 'admin/reports/profit-loss*', 'permission' => 'reports.view'],
            ],
        ],
        [
            'id' => 'menuWebsite',
            'icon' => '🌐',
            'label' => 'Website',
            'links' => [
                ['label' => 'Notices', 'url' => '/admin/website/notices', 'pattern' => 'admin/website/notices*', 'permission' => 'notices.view'],
                ['label' => 'Gallery', 'url' => '/admin/website/gallery', 'pattern' => 'admin/website/gallery*', 'permission' => 'gallery.view'],
                ['label' => 'Testimonials', 'url' => '/admin/website/testimonials', 'pattern' => 'admin/website/testimonials*', 'permission' => 'testimonials.view'],
            ],
        ],
        [
            'id' => 'menuSystem',
            'icon' => '⚙️',
            'label' => 'System',
            'links' => [
                ['label' => 'Settings', 'url' => '/admin/settings', 'pattern' => 'admin/settings*', 'permission' => 'settings.view'],
                ['label' => 'Activity Log', 'url' => '/admin/activity-log', 'pattern' => 'admin/activity-log*', 'permission' => 'activity-logs.view'],
            ],
        ],
    ];
@endphp

<div class="col-md-2 admin-sidebar">

    <div class="sidebar-brand">
        @if(\App\Models\Setting::get('institute_logo'))
            <img src="{{ asset('storage/' . \App\Models\Setting::get('institute_logo')) }}">
        @endif
        <h6 class="mb-0 fw-bold">{{ \App\Models\Setting::get('institute_name', 'Coaching ERP') }}</h6>
    </div>

    <div class="sidebar-inner">

        <!-- Dashboard -->
        @role('teacher')
        <a href="/teacher/dashboard"
            class="nav-link {{ request()->is('teacher/dashboard') ? 'active' : '' }}">
            <span>📊</span> My Dashboard
        </a>

        <a href="/teacher/batches"
            class="nav-link {{ request()->is('teacher/batches') ? 'active' : '' }}">
            <span>🗂️</span> My Batches
        </a>

        <a href="/teacher/attendance"
            class="nav-link {{ request()->is('teacher/attendance') ? 'active' : '' }}">
            <span>✅</span> Mark Attendance
        </a>

        <a href="/teacher/marks"
            class="nav-link {{ request()->is('teacher/marks') ? 'active' : '' }}">
            <span>📝</span> Enter Marks
        </a>

        <a href="/teacher/timetable"
            class="nav-link {{ request()->is('teacher/timetable') ? 'active' : '' }}">
            <span>🗓️</span> My Timetable
        </a>

        <a href="/teacher/study-material"
            class="nav-link {{ request()->is('teacher/study-material') ? 'active' : '' }}">
            <span>📚</span> Study Material
        </a>

        <a href="/teacher/homework"
            class="nav-link {{ request()->is('teacher/homework') ? 'active' : '' }}">
            <span>📔</span> Homework
        </a>

        <a href="/profile"
            class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
            <span>👤</span> My Profile
        </a>
        @elserole('student')
        <a href="/student/dashboard"
            class="nav-link {{ request()->is('student/dashboard') ? 'active' : '' }}">
            <span>📊</span> My Dashboard
        </a>

        <a href="/student/attendance"
            class="nav-link {{ request()->is('student/attendance') ? 'active' : '' }}">
            <span>📋</span> My Attendance
        </a>

        <a href="/student/fees"
            class="nav-link {{ request()->is('student/fees') ? 'active' : '' }}">
            <span>💰</span> My Fees
        </a>

        <a href="/student/results"
            class="nav-link {{ request()->is('student/results') ? 'active' : '' }}">
            <span>📝</span> My Results
        </a>

        <a href="/student/timetable"
            class="nav-link {{ request()->is('student/timetable') ? 'active' : '' }}">
            <span>🗓️</span> My Timetable
        </a>

        <a href="/student/study-material"
            class="nav-link {{ request()->is('student/study-material') ? 'active' : '' }}">
            <span>📚</span> Study Material
        </a>

        <a href="/student/homework"
            class="nav-link {{ request()->is('student/homework') ? 'active' : '' }}">
            <span>📔</span> Homework
        </a>

        <a href="/profile"
            class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
            <span>👤</span> My Profile
        </a>
        @elserole('parent')
        <a href="/parent/dashboard"
            class="nav-link {{ request()->is('parent/dashboard') ? 'active' : '' }}">
            <span>📊</span> My Dashboard
        </a>

        <a href="/parent/attendance"
            class="nav-link {{ request()->is('parent/attendance') ? 'active' : '' }}">
            <span>📋</span> Attendance
        </a>

        <a href="/parent/fees"
            class="nav-link {{ request()->is('parent/fees') ? 'active' : '' }}">
            <span>💰</span> Fees
        </a>

        <a href="/parent/results"
            class="nav-link {{ request()->is('parent/results') ? 'active' : '' }}">
            <span>📝</span> Results
        </a>

        <a href="/profile"
            class="nav-link {{ request()->is('profile') ? 'active' : '' }}">
            <span>👤</span> My Profile
        </a>
        @else
        <a href="/dashboard"
            class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
            <span>📊</span> Dashboard
        </a>
        @endrole

        @foreach($menuSections as $section)

            @php
                $visibleLinks = collect($section['links'])
                    ->filter(fn ($link) => auth()->user()->can($link['permission']));

                $sectionActive = $visibleLinks->contains(fn ($link) => request()->is($link['pattern']));
            @endphp

            @if($visibleLinks->isNotEmpty())

                <button
                    class="section-toggle {{ $sectionActive ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse"
                    data-bs-target="#{{ $section['id'] }}">

                    <span>{{ $section['icon'] }} {{ $section['label'] }}</span>

                    <span class="chev">▼</span>

                </button>

                <div class="collapse {{ $sectionActive ? 'show' : '' }}" id="{{ $section['id'] }}">

                    @foreach($visibleLinks as $link)

                        <a href="{{ $link['url'] }}"
                            class="nav-link {{ request()->is($link['pattern']) ? 'active' : '' }}">
                            <span>•</span> {{ $link['label'] }}
                        </a>

                    @endforeach

                </div>

            @endif

        @endforeach

    </div>

</div>
