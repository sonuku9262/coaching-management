<div class="col-md-2 bg-white border-end min-vh-100 shadow-sm p-0">

    <div class="text-center py-3 bg-primary text-white border-bottom">
        <h5 class="mb-0 fw-bold">Coaching ERP</h5>
    </div>

    <div class="p-2">

        <!-- Dashboard -->
        <a href="/dashboard"
            class="nav-link mb-1 {{ request()->is('dashboard') ? 'bg-primary text-white rounded' : 'text-dark' }}">
            📊 Dashboard
        </a>

        <hr>

        <!-- User Management -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            User Management
        </h6>

        <a href="/roles"
            class="nav-link ps-3 {{ request()->is('roles*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Roles
        </a>

        <a href="/users"
            class="nav-link ps-3 {{ request()->is('users*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Users
        </a>

        <a href="/admin/permissions"
            class="nav-link ps-3 {{ request()->is('admin/permissions*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Permissions
        </a>

        <hr>

        <!-- Master Data -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Master Data
        </h6>

        <a href="/admin/academic-years"
            class="nav-link ps-3 {{ request()->is('admin/academic-years*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Academic Year
        </a>

        <a href="/admin/academic-sessions"
            class="nav-link ps-3 {{ request()->is('admin/academic-sessions*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Academic Session
        </a>

        <a href="/admin/courses"
            class="nav-link ps-3 {{ request()->is('admin/courses*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Courses
        </a>

        <a href="/admin/subjects"
            class="nav-link ps-3 {{ request()->is('admin/subjects*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Subjects
        </a>

        <a href="/admin/batches"
            class="nav-link ps-3 {{ request()->is('admin/batches*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Batches
        </a>

        <a href="/admin/classrooms"
            class="nav-link ps-3 {{ request()->is('admin/classrooms*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Classrooms
        </a>

        <a href="/admin/shifts"
            class="nav-link ps-3 {{ request()->is('admin/shifts*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Shifts
        </a>

        <hr>

        <!-- Student -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Student Management
        </h6>

        <a href="/admin/student-registrations"
            class="nav-link ps-3 {{ request()->is('admin/student-registrations*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Student Registration
        </a>

        <hr>

        <!-- Teacher -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Teacher Management
        </h6>

        <a href="/admin/teachers"
            class="nav-link ps-3 {{ request()->is('admin/teachers*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Teachers
        </a>

        <hr>

        <!-- Fee -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Fee Management
        </h6>

        <a href="/admin/fee-types"
            class="nav-link ps-3 {{ request()->is('admin/fee-types*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Type
        </a>

        <a href="/admin/fee-structures"
            class="nav-link ps-3 {{ request()->is('admin/fee-structures*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Structure
        </a>

        <a href="/admin/fee-collections"
            class="nav-link ps-3 {{ request()->is('admin/fee-collections*') ? 'text-primary fw-bold' : 'text-dark' }}">
            • Fee Collection
        </a>

        <hr>

        <!-- Attendance -->
        <h6 class="text-primary fw-bold mt-3 mb-2">
            Attendance
        </h6>

        <a href="#" class="nav-link ps-3 text-dark">
            • Student Attendance
        </a>

        <a href="#" class="nav-link ps-3 text-dark">
            • Teacher Attendance
        </a>

        <hr>

        <!-- Examination -->
        <a href="#" class="nav-link text-dark">
            📝 Examination
        </a>

        <!-- Reports -->
        <a href="#" class="nav-link text-dark">
            📊 Reports
        </a>

        <!-- Settings -->
        <a href="#" class="nav-link text-dark">
            ⚙️ Settings
        </a>

    </div>

</div>