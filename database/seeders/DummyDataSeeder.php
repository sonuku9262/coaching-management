<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\AcademicYear;
use App\Models\Batch;
use App\Models\Classroom;
use App\Models\ClassTimetable;
use App\Models\Course;
use App\Models\Enquiry;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\ExamSchedule;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\FeeCollection;
use App\Models\FeeStructure;
use App\Models\FeeType;
use App\Models\Homework;
use App\Models\Notice;
use App\Models\Setting;
use App\Models\Shift;
use App\Models\StudentAttendance;
use App\Models\StudentRegistration;
use App\Models\StudyMaterial;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use App\Models\TeacherBatchSubject;
use App\Models\TeacherSalaryPayment;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Support\ActivityLogStatus;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        // keep the audit trail clean while seeding demo data
        app(ActivityLogStatus::class)->disable();

        $this->call(RolePermissionSeeder::class);

        // ------------------------------------------------ settings
        Setting::set('institute_name', Setting::get('institute_name', 'Sunrise Coaching Institute'));
        Setting::set('institute_email', Setting::get('institute_email', 'info@sunrisecoaching.test'));
        Setting::set('institute_phone', Setting::get('institute_phone', '9876500001'));
        Setting::set('institute_address', Setting::get('institute_address', 'Main Road, Near City Mall, Patna, Bihar - 800001'));

        // ------------------------------------------------ academic setup
        $year = AcademicYear::firstOrCreate(
            ['name' => '2026-27'],
            ['start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true],
        );

        $session = AcademicSession::firstOrCreate(
            ['name' => 'April 2026 Session', 'academic_year_id' => $year->id],
            ['start_date' => '2026-04-01', 'end_date' => '2027-03-31', 'status' => true],
        );

        $morningShift = Shift::firstOrCreate(
            ['name' => 'Morning'],
            ['start_time' => '07:00', 'end_time' => '11:00', 'status' => true],
        );

        $eveningShift = Shift::firstOrCreate(
            ['name' => 'Evening'],
            ['start_time' => '16:00', 'end_time' => '20:00', 'status' => true],
        );

        $roomA = Classroom::firstOrCreate(
            ['name' => 'Room A'],
            ['room_no' => '101', 'floor' => '1st', 'capacity' => 40, 'status' => true],
        );

        $roomB = Classroom::firstOrCreate(
            ['name' => 'Room B'],
            ['room_no' => '102', 'floor' => '1st', 'capacity' => 30, 'status' => true],
        );

        // ------------------------------------------------ courses / subjects / batches
        $courseData = [
            [
                'name' => 'Class 10 Foundation',
                'code' => 'C10',
                'fees' => 24000,
                'subjects' => ['Mathematics', 'Science', 'English'],
            ],
            [
                'name' => 'Class 12 Science (PCM)',
                'code' => 'C12PCM',
                'fees' => 36000,
                'subjects' => ['Physics', 'Chemistry', 'Mathematics'],
            ],
            [
                'name' => 'Competitive Exam Batch',
                'code' => 'COMP',
                'fees' => 30000,
                'subjects' => ['Quantitative Aptitude', 'Reasoning', 'General Studies'],
            ],
        ];

        $courses = collect();

        foreach ($courseData as $index => $data) {
            $course = Course::firstOrCreate(
                ['code' => $data['code']],
                [
                    'name' => $data['name'],
                    'duration' => 12,
                    'duration_type' => 'Months',
                    'fees' => $data['fees'],
                    'description' => $data['name'] . ' — expert faculty, weekly tests aur full study material ke saath.',
                    'status' => true,
                ],
            );

            foreach ($data['subjects'] as $subjectIndex => $subjectName) {
                Subject::firstOrCreate(
                    ['course_id' => $course->id, 'name' => $subjectName],
                    ['code' => $data['code'] . '-S' . ($subjectIndex + 1), 'status' => true],
                );
            }

            Batch::firstOrCreate(
                ['course_id' => $course->id, 'name' => $data['code'] . ' Batch A'],
                [
                    'academic_year_id' => $year->id,
                    'academic_session_id' => $session->id,
                    'start_date' => '2026-04-15',
                    'end_date' => '2027-03-15',
                    'capacity' => 40,
                    'status' => true,
                ],
            );

            $courses->push($course->fresh());
        }

        // ------------------------------------------------ fee types & structures
        $admissionFee = FeeType::firstOrCreate(
            ['name' => 'Admission Fee'],
            ['code' => 'ADM', 'description' => 'One time admission fee', 'status' => true],
        );

        $tuitionFee = FeeType::firstOrCreate(
            ['name' => 'Tuition Fee'],
            ['code' => 'TUT', 'description' => 'Monthly tuition fee', 'status' => true],
        );

        $examFee = FeeType::firstOrCreate(
            ['name' => 'Exam Fee'],
            ['code' => 'EXM', 'description' => 'Term exam fee', 'status' => true],
        );

        foreach ($courses as $course) {
            FeeStructure::firstOrCreate(
                ['course_id' => $course->id, 'fee_type_id' => $admissionFee->id],
                ['amount' => 2000, 'installments' => 1, 'status' => true],
            );

            FeeStructure::firstOrCreate(
                ['course_id' => $course->id, 'fee_type_id' => $tuitionFee->id],
                ['amount' => $course->fees, 'installments' => 12, 'status' => true],
            );

            FeeStructure::firstOrCreate(
                ['course_id' => $course->id, 'fee_type_id' => $examFee->id],
                ['amount' => 500, 'installments' => 2, 'status' => true],
            );
        }

        // ------------------------------------------------ staff logins
        $this->userWithRole('Demo Admin', 'admin@demo.test', 'admin');
        $this->userWithRole('Demo Accountant', 'accountant@demo.test', 'accountant');

        // ------------------------------------------------ teachers
        $teacherData = [
            ['Rakesh Sharma', 'M.Sc. Physics', 'teacher@demo.test', '9876510001'],
            ['Anita Verma', 'M.Sc. Chemistry', 'anita.verma@demo.test', '9876510002'],
            ['Sunil Kumar', 'M.A. English, B.Ed.', 'sunil.kumar@demo.test', '9876510003'],
            ['Pooja Singh', 'M.Sc. Mathematics', 'pooja.singh@demo.test', '9876510004'],
            ['Vikas Gupta', 'M.A. General Studies', 'vikas.gupta@demo.test', '9876510005'],
        ];

        $teachers = collect();

        foreach ($teacherData as $index => [$name, $qualification, $email, $mobile]) {
            $teacher = Teacher::firstOrCreate(
                ['email' => $email],
                [
                    'employee_id' => 'EMP-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'name' => $name,
                    'mobile' => $mobile,
                    'qualification' => $qualification,
                    'experience' => (5 + $index) . ' years',
                    'address' => 'Patna, Bihar',
                    'joining_date' => '2026-04-01',
                    'salary' => 30000 + ($index * 2500),
                    'status' => true,
                ],
            );

            if (! $teacher->user_id) {
                $user = $this->userWithRole($name, $email, 'teacher');
                $teacher->update(['user_id' => $user->id]);
            }

            $teachers->push($teacher->fresh());
        }

        // ------------------------------------------------ teacher batch/subject assignments + timetable
        $assignmentMap = [
            'teacher@demo.test' => [['C12PCM', 'Physics']],
            'anita.verma@demo.test' => [['C12PCM', 'Chemistry']],
            'sunil.kumar@demo.test' => [['C10', 'English']],
            'pooja.singh@demo.test' => [['C10', 'Mathematics'], ['C12PCM', 'Mathematics']],
            'vikas.gupta@demo.test' => [['COMP', 'Quantitative Aptitude']],
        ];

        $batchSlotHour = [];

        foreach ($teachers as $teacher) {
            foreach ($assignmentMap[$teacher->email] ?? [] as [$courseCode, $subjectName]) {
                $course = $courses->firstWhere('code', $courseCode);
                $batch = $course ? Batch::where('course_id', $course->id)->first() : null;
                $subject = $course ? Subject::where('course_id', $course->id)->where('name', $subjectName)->first() : null;

                if (! $batch || ! $subject) {
                    continue;
                }

                TeacherBatchSubject::firstOrCreate([
                    'teacher_id' => $teacher->id,
                    'batch_id' => $batch->id,
                    'subject_id' => $subject->id,
                ]);

                // stagger start times so multiple subjects on the same batch/day don't collide
                $hour = $batchSlotHour[$batch->id] ??= 9;
                $batchSlotHour[$batch->id]++;

                foreach (['Monday', 'Wednesday'] as $day) {
                    ClassTimetable::firstOrCreate(
                        ['batch_id' => $batch->id, 'day_of_week' => $day, 'start_time' => sprintf('%02d:00', $hour)],
                        [
                            'subject_id' => $subject->id,
                            'teacher_id' => $teacher->id,
                            'classroom_id' => $roomA->id,
                            'end_time' => sprintf('%02d:00', $hour + 1),
                        ],
                    );
                }

                StudyMaterial::firstOrCreate(
                    ['batch_id' => $batch->id, 'subject_id' => $subject->id, 'title' => $subject->name . ' — Chapter 1 Notes'],
                    ['teacher_id' => $teacher->id, 'description' => 'Introductory notes for ' . $subject->name . '.', 'status' => true],
                );

                Homework::firstOrCreate(
                    ['batch_id' => $batch->id, 'subject_id' => $subject->id, 'title' => $subject->name . ' — Practice Set 1'],
                    [
                        'teacher_id' => $teacher->id,
                        'description' => 'Solve the practice questions and submit in class.',
                        'due_date' => now()->addDays(5)->toDateString(),
                        'status' => true,
                    ],
                );
            }
        }

        // ------------------------------------------------ students (+ demo logins)
        $studentNames = [
            'Aman Kumar', 'Priya Sharma', 'Rahul Singh', 'Sneha Gupta', 'Vivek Yadav',
            'Anjali Kumari', 'Rohit Verma', 'Khushi Singh', 'Deepak Kumar', 'Neha Mishra',
            'Sanjay Paswan', 'Ritika Jha', 'Mohit Raj', 'Kajal Kumari', 'Aditya Ranjan',
            'Simran Kaur', 'Nitish Kumar', 'Payal Singh', 'Gaurav Pandey', 'Shreya Sinha',
        ];

        $students = collect();

        foreach ($studentNames as $index => $name) {
            $course = $courses[$index % $courses->count()];
            $batch = Batch::where('course_id', $course->id)->first();
            $emailName = strtolower(str_replace(' ', '.', $name));

            $student = StudentRegistration::firstOrCreate(
                ['admission_no' => 'ADM-2026-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT)],
                [
                    'academic_year_id' => $year->id,
                    'academic_session_id' => $session->id,
                    'course_id' => $course->id,
                    'batch_id' => $batch->id,
                    'classroom_id' => $index % 2 === 0 ? $roomA->id : $roomB->id,
                    'shift_id' => $index % 2 === 0 ? $morningShift->id : $eveningShift->id,
                    'name' => $name,
                    'father_name' => 'Shri ' . explode(' ', $name)[1] . ' Prasad',
                    'mother_name' => 'Smt. Sunita Devi',
                    'guardian_email' => $index === 0 ? 'parent@demo.test' : null,
                    'gender' => $index % 3 === 1 ? 'Female' : 'Male',
                    'dob' => '20' . str_pad(8 + ($index % 4), 2, '0', STR_PAD_LEFT) . '-0' . (($index % 9) + 1) . '-15',
                    'mobile' => '98765' . str_pad(20000 + $index, 5, '0', STR_PAD_LEFT),
                    'email' => $index === 0 ? 'student@demo.test' : $emailName . '@demo.test',
                    'address' => 'Ward ' . ($index + 1) . ', Patna, Bihar',
                    'admission_date' => '2026-04-' . str_pad(($index % 25) + 1, 2, '0', STR_PAD_LEFT),
                    'status' => true,
                ],
            );

            $students->push($student->fresh());
        }

        // demo student + parent logins (password: "password")
        $demoStudent = $students->first();

        if (! $demoStudent->user_id) {
            $user = $this->userWithRole($demoStudent->name, 'student@demo.test', 'student');
            $demoStudent->update(['user_id' => $user->id]);
        }

        if (! $demoStudent->guardian_user_id) {
            $guardian = $this->userWithRole('Parent of ' . $demoStudent->name, 'parent@demo.test', 'parent');
            $demoStudent->update(['guardian_user_id' => $guardian->id]);
        }

        // ------------------------------------------------ fee collections
        {
            $receiptNo = (FeeCollection::max('id') ?? 0) + 1;

            foreach ($students as $index => $student) {
                if ($student->feeCollections()->exists()) {
                    continue;
                }

                // admission fee — everyone paid
                FeeCollection::create([
                    'student_registration_id' => $student->id,
                    'fee_type_id' => $admissionFee->id,
                    'amount' => 2000,
                    'discount' => 0,
                    'fine' => 0,
                    'paid_amount' => 2000,
                    'balance' => 0,
                    'payment_mode' => 'Cash',
                    'receipt_no' => 'RCPT-2026-' . str_pad($receiptNo++, 5, '0', STR_PAD_LEFT),
                    'payment_date' => $student->admission_date,
                    'status' => true,
                ]);

                // tuition installment — every 3rd student has a pending balance
                $installment = 3000;
                $paid = $index % 3 === 0 ? 1500 : $installment;

                FeeCollection::create([
                    'student_registration_id' => $student->id,
                    'fee_type_id' => $tuitionFee->id,
                    'amount' => $installment,
                    'discount' => $index % 5 === 0 ? 500 : 0,
                    'fine' => 0,
                    'paid_amount' => $paid,
                    'balance' => max(0, $installment - ($index % 5 === 0 ? 500 : 0) - $paid),
                    'payment_mode' => $index % 2 === 0 ? 'Cash' : 'UPI',
                    'receipt_no' => 'RCPT-2026-' . str_pad($receiptNo++, 5, '0', STR_PAD_LEFT),
                    'payment_date' => now()->subDays(rand(1, 20))->toDateString(),
                    'status' => true,
                ]);
            }
        }

        // ------------------------------------------------ expense categories, expenses & salary payments
        $expenseCategoryData = [
            ['Rent', 'RENT'],
            ['Electricity', 'ELEC'],
            ['Stationery', 'STAT'],
            ['Marketing', 'MKTG'],
            ['Maintenance', 'MAINT'],
        ];

        $expenseCategories = collect();

        foreach ($expenseCategoryData as [$name, $code]) {
            $expenseCategories->push(ExpenseCategory::firstOrCreate(
                ['code' => $code],
                ['name' => $name, 'status' => true],
            ));
        }

        if (Expense::count() === 0) {
            $expenseVoucher = 1;

            foreach ([
                ['Rent', 15000, 'Landlord — Mr. Sharma', 1],
                ['Electricity', 3200, 'State Electricity Board', 3],
                ['Stationery', 1800, 'Patna Stationers', 5],
                ['Marketing', 2500, 'Local Newspaper Ad', 10],
                ['Maintenance', 1200, 'AC Service', 12],
            ] as [$categoryName, $amount, $paidTo, $daysAgo]) {
                Expense::create([
                    'expense_category_id' => $expenseCategories->firstWhere('name', $categoryName)->id,
                    'amount' => $amount,
                    'expense_date' => now()->subDays($daysAgo)->toDateString(),
                    'payment_mode' => 'Cash',
                    'paid_to' => $paidTo,
                    'voucher_no' => 'EXP-2026-' . str_pad($expenseVoucher++, 5, '0', STR_PAD_LEFT),
                    'status' => true,
                ]);
            }
        }

        if (TeacherSalaryPayment::count() === 0) {
            $salaryVoucher = 1;

            foreach ($teachers as $teacher) {
                TeacherSalaryPayment::create([
                    'teacher_id' => $teacher->id,
                    'salary_month' => now()->subMonthNoOverflow()->startOfMonth()->toDateString(),
                    'amount' => $teacher->salary,
                    'deduction' => 0,
                    'paid_amount' => $teacher->salary,
                    'payment_date' => now()->subMonthNoOverflow()->startOfMonth()->addDays(4)->toDateString(),
                    'payment_mode' => 'Bank Transfer',
                    'voucher_no' => 'SAL-2026-' . str_pad($salaryVoucher++, 5, '0', STR_PAD_LEFT),
                ]);
            }
        }

        // ------------------------------------------------ attendance (last 14 days, weekdays)
        for ($daysAgo = 13; $daysAgo >= 0; $daysAgo--) {
            $date = now()->subDays($daysAgo);

            if ($date->isWeekend()) {
                continue;
            }

            foreach ($students as $index => $student) {
                $roll = ($index + $daysAgo) % 10;

                StudentAttendance::updateOrCreate(
                    [
                        'student_registration_id' => $student->id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'course_id' => $student->course_id,
                        'batch_id' => $student->batch_id,
                        'status' => $roll === 0 ? 'Absent' : ($roll === 1 ? 'Leave' : 'Present'),
                        'remarks' => null,
                    ],
                );
            }

            foreach ($teachers as $index => $teacher) {
                TeacherAttendance::updateOrCreate(
                    [
                        'teacher_id' => $teacher->id,
                        'attendance_date' => $date->toDateString(),
                    ],
                    [
                        'status' => ($index + $daysAgo) % 12 === 0 ? 'Absent' : 'Present',
                        'remarks' => null,
                    ],
                );
            }
        }

        // ------------------------------------------------ exams, schedules & results
        if (Exam::count() === 0) {
            foreach ($courses as $course) {
                $batch = Batch::where('course_id', $course->id)->first();

                $exam = Exam::create([
                    'name' => 'Monthly Test — June ' . now()->year,
                    'course_id' => $course->id,
                    'batch_id' => $batch->id,
                    'start_date' => now()->subDays(14)->toDateString(),
                    'end_date' => now()->subDays(11)->toDateString(),
                    'status' => true,
                ]);

                $subjects = Subject::where('course_id', $course->id)->get();
                $courseStudents = $students->where('course_id', $course->id);

                foreach ($subjects as $subjectIndex => $subject) {
                    $schedule = ExamSchedule::create([
                        'exam_id' => $exam->id,
                        'subject_id' => $subject->id,
                        'exam_date' => now()->subDays(14 - $subjectIndex)->toDateString(),
                        'start_time' => '10:00',
                        'end_time' => '12:00',
                        'total_marks' => 100,
                        'passing_marks' => 33,
                    ]);

                    foreach ($courseStudents as $studentIndex => $student) {
                        $absent = ($studentIndex + $subjectIndex) % 11 === 0;

                        ExamResult::create([
                            'exam_schedule_id' => $schedule->id,
                            'student_registration_id' => $student->id,
                            'marks_obtained' => $absent ? null : rand(25, 96),
                            'is_absent' => $absent,
                        ]);
                    }
                }

                // upcoming exam for the student dashboard
                $upcoming = Exam::create([
                    'name' => 'Weekly Test — ' . now()->addDays(5)->format('d M'),
                    'course_id' => $course->id,
                    'batch_id' => $batch->id,
                    'start_date' => now()->addDays(5)->toDateString(),
                    'end_date' => now()->addDays(7)->toDateString(),
                    'status' => true,
                ]);

                foreach ($subjects->take(2) as $subjectIndex => $subject) {
                    ExamSchedule::create([
                        'exam_id' => $upcoming->id,
                        'subject_id' => $subject->id,
                        'exam_date' => now()->addDays(5 + $subjectIndex)->toDateString(),
                        'start_time' => '10:00',
                        'end_time' => '12:00',
                        'total_marks' => 50,
                        'passing_marks' => 17,
                    ]);
                }
            }
        }

        // ------------------------------------------------ website content
        $notices = [
            ['Admission Open — New Batches Starting Soon', 'Sabhi courses ke naye batches me admission chalu hai. Office se sampark karein.', 0],
            ['Weekly Test Schedule Published', 'Agale hafte ke weekly tests ka schedule portal par dekh sakte hain.', 1],
            ['Holiday Notice', 'Aane wale Sunday ko institute band rahega.', 3],
            ['Fee Reminder', 'Jin students ka balance due hai, kripya is month ke andar jama karein.', 5],
        ];

        foreach ($notices as [$title, $description, $daysAgo]) {
            Notice::firstOrCreate(
                ['title' => $title],
                [
                    'description' => $description,
                    'notice_date' => now()->subDays($daysAgo)->toDateString(),
                    'show_on_website' => true,
                    'status' => true,
                ],
            );
        }

        $testimonials = [
            ['Aman Kumar', 'Class 10 Student', 'Teachers bahut acche hain aur har doubt clear karte hain. Weekly tests se meri preparation strong hui.', 5],
            ['Sunita Devi', 'Parent', 'Bachhe ki attendance aur fees ki jaankari phone par mil jaati hai. Bahut acchi vyavastha hai.', 5],
            ['Rahul Singh', 'Class 12 Student', 'PCM ki coaching yahan ki best hai. Study material bhi bahut helpful hai.', 4],
            ['Priya Sharma', 'Competitive Batch', 'Mock tests aur personal guidance se mujhe bahut fayda hua.', 5],
        ];

        foreach ($testimonials as [$name, $designation, $message, $rating]) {
            Testimonial::firstOrCreate(
                ['name' => $name, 'designation' => $designation],
                ['message' => $message, 'rating' => $rating, 'status' => true],
            );
        }

        $enquiries = [
            ['Rajesh Kumar', '9876530001', 'rajesh.k@example.com', 'Class 10 Foundation ke baare me fees aur timing janna hai.', 'new'],
            ['Meena Kumari', '9876530002', null, 'Beti ke liye Class 12 PCM batch me admission karana hai.', 'new'],
            ['Suresh Yadav', '9876530003', 'suresh.y@example.com', 'Competitive exam batch ki details chahiye.', 'contacted'],
            ['Kavita Singh', '9876530004', null, 'Morning shift me seat available hai kya?', 'contacted'],
            ['Manoj Prasad', '9876530005', null, 'Demo class attend karna chahta hoon.', 'closed'],
        ];

        foreach ($enquiries as $index => [$name, $mobile, $email, $message, $status]) {
            Enquiry::firstOrCreate(
                ['mobile' => $mobile],
                [
                    'name' => $name,
                    'email' => $email,
                    'course_id' => $courses[$index % $courses->count()]->id,
                    'message' => $message,
                    'status' => $status,
                ],
            );
        }

        app(ActivityLogStatus::class)->enable();

        $this->command?->info('Dummy data seeded. Demo logins (password: "password"):');
        $this->command?->table(
            ['Role', 'Email'],
            [
                ['Super Admin', 'admin@coaching.test'],
                ['Admin', 'admin@demo.test'],
                ['Accountant', 'accountant@demo.test'],
                ['Teacher', 'teacher@demo.test'],
                ['Student', 'student@demo.test'],
                ['Parent', 'parent@demo.test'],
            ],
        );
    }

    protected function userWithRole(string $name, string $email, string $role): User
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'status' => true,
            ],
        );

        $user->syncRoles([$role]);

        return $user;
    }
}
