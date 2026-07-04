<div class="col-md-2 bg-white border-end min-vh-100 shadow-sm p-0">

    <div class="text-center py-3 bg-primary text-white border-bottom">
        <h5 class="mb-0 fw-bold">Coaching ERP</h5>
    </div>

    <div class="p-2">

        <!-- Dashboard -->
        @role('teacher')
        <a href="/teacher/dashboard"
            class="nav-link mb-1 {{ request()->is('teacher/dashboard') ? 'bg-primary text-white rounded' : 'text-dark' }}">
            📊 My Dashboard
        </a>
        @elserole('student')
        <a href="/student/dashboard"
            class="nav-link mb-1 {{ request()->is('student/dashboard') ? 'bg-primary text-white rounded' : 'text-dark' }}">
            📊 My Dashboard
        </a>
        @elserole('parent')
        <a href="/parent/dashboard"
            class="nav-link mb-1 {{ request()->is('parent/dashboard') ? 'bg-primary text-white rounded' : 'text-dark' }}">
            📊 My Dashboard
        </a>
        @else
        <a href="/dashboard"
            class="nav-link mb-1 {{ request()->is('dashboard') ? 'bg-primary text-white rounded' : 'text-dark' }}">
            📊 Dashboard
        </a>
        @endrole

        @canany(['roles.view', 'users.view', 'permissions.view'])
        <hr>

        <!-- User Management -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            User Management
        </h6>

        @can('roles.view')
        <a href="/admin/roles" class="nav-link ps-3 {{ request()->is('admin/roles*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Roles
        </a>
        @endcan

        @can('users.view')
        <a href="/admin/users" class="nav-link ps-3 {{ request()->is('admin/users*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Users
        </a>
        @endcan

        @can('permissions.view')
        <a href="/admin/permissions"
            class="nav-link ps-3 {{ request()->is('admin/permissions*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Permissions
        </a>
        @endcan
        @endcanany

        @canany(['academic-years.view', 'academic-sessions.view', 'courses.view', 'subjects.view', 'batches.view', 'classrooms.view', 'shifts.view'])
        <hr>

        <!-- Master Data -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Master Data
        </h6>

        @can('academic-years.view')
        <a href="/admin/academic-years"
            class="nav-link ps-3 {{ request()->is('admin/academic-years*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Academic Year
        </a>
        @endcan

        @can('academic-sessions.view')
        <a href="/admin/academic-sessions"
            class="nav-link ps-3 {{ request()->is('admin/academic-sessions*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Academic Session
        </a>
        @endcan

        @can('courses.view')
        <a href="/admin/courses"
            class="nav-link ps-3 {{ request()->is('admin/courses*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Courses
        </a>
        @endcan

        @can('subjects.view')
        <a href="/admin/subjects"
            class="nav-link ps-3 {{ request()->is('admin/subjects*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Subjects
        </a>
        @endcan

        @can('batches.view')
        <a href="/admin/batches"
            class="nav-link ps-3 {{ request()->is('admin/batches*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Batches
        </a>
        @endcan

        @can('classrooms.view')
        <a href="/admin/classrooms"
            class="nav-link ps-3 {{ request()->is('admin/classrooms*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Classrooms
        </a>
        @endcan

        @can('shifts.view')
        <a href="/admin/shifts"
            class="nav-link ps-3 {{ request()->is('admin/shifts*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Shifts
        </a>
        @endcan
        @endcanany

        @can('students.view')
        <hr>

        <!-- Student -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Student Management
        </h6>

        <a href="/admin/student-registrations"
            class="nav-link ps-3 {{ request()->is('admin/student-registrations*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Student Registration
        </a>
        @endcan

        @can('teachers.view')
        <hr>

        <!-- Teacher -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Teacher Management
        </h6>

        <a href="/admin/teachers"
            class="nav-link ps-3 {{ request()->is('admin/teachers*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Teachers
        </a>
        @endcan

        @canany(['fee-types.view', 'fee-structures.view', 'fee-collections.view'])
        <hr>

        <!-- Fee -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Fee Management
        </h6>

        @can('fee-types.view')
        <a href="/admin/fee-types"
            class="nav-link ps-3 {{ request()->is('admin/fee-types*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Type
        </a>
        @endcan

        @can('fee-structures.view')
        <a href="/admin/fee-structures"
            class="nav-link ps-3 {{ request()->is('admin/fee-structures*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Structure
        </a>
        @endcan

        @can('fee-collections.view')
        <a href="/admin/fee-collections"
            class="nav-link ps-3 {{ request()->is('admin/fee-collections*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Collection
        </a>
        @endcan
        @endcanany

        @canany(['student-attendance.view', 'teacher-attendance.view'])
        <hr>

        <!-- Attendance -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Attendance
        </h6>

        @can('student-attendance.view')
        <a href="/admin/student-attendance"
            class="nav-link ps-3 {{ request()->is('admin/student-attendance*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Student Attendance
        </a>
        @endcan

        @can('teacher-attendance.view')
        <a href="/admin/teacher-attendance"
            class="nav-link ps-3 {{ request()->is('admin/teacher-attendance*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Teacher Attendance
        </a>
        @endcan
        @endcanany

        @canany(['exams.view', 'exam-results.view'])
        <hr>

        <!-- Examination -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Examination
        </h6>

        @can('exams.view')
        <a href="/admin/exams"
            class="nav-link ps-3 {{ request()->is('admin/exams*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Exams
        </a>
        @endcan

        @can('exam-results.view')
        <a href="/admin/exam-results"
            class="nav-link ps-3 {{ request()->is('admin/exam-results*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Marks Entry
        </a>
        @endcan
        @endcanany

        @can('reports.view')
        <hr>

        <!-- Reports -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Reports
        </h6>

        <a href="/admin/reports/fees"
            class="nav-link ps-3 {{ request()->is('admin/reports/fees*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Collection
        </a>

        <a href="/admin/reports/attendance"
            class="nav-link ps-3 {{ request()->is('admin/reports/attendance*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Attendance
        </a>
        @endcan

        @can('settings.view')
        <!-- Settings -->
        <a href="#" class="nav-link text-dark">
            ⚙️ Settings
        </a>
        @endcan

    </div>

</div>
