<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcript of Records</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #000;
        }

        @page {
            size: A4;
            margin: 1in;
        }

        .container {
            width: 100%;
            margin: 0 auto;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header p, .header .program-title {
            margin: 5px 0;
        }

        .header .institution-name {
            font-size: 14px;
            font-weight: bold;
        }

        .program-title {
            font-size: 12px;
            font-weight: bold;
        }

        .year-container {
            margin-bottom: 20px;
            text-align: center; /* Center the year title */
        }

        .year-title {
            font-weight: bold;
            font-size: 12px;
            margin: 15px 0;
        }

        .semester-columns {
            display: flex;
            justify-content: space-between; /* Space between tables */
        }

        .semester-container {
            width: 100%; /* Set width for each semester table */
            text-align: center; /* Center titles in tables */
        }

        .semester-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .semester-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }

        .semester-table th, .semester-table td {
            padding: 5px;
            border: 1px solid #000;
            text-align: left;
        }

        .semester-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="institution-name">Philippine Christian University</div>
    <p>Taft Ave., Manila</p>
    <p class="program-title">{{ $user->curriculum->curriculum_name }}</p>
    <p class="program-title">Program: {{$user->course->course_name}}</p>
    <p><strong>Name:</strong> {{ $user->name }} {{ $user->last_name }}</p>
</div>

<div class="container">
    @foreach ($groupedSubjects as $year => $yearTerms)
        <div class="year-container">
            <div class="year-title">{{ $year }}</div>

            <div class="semester-columns">
                {{-- 1st Semester Table --}}
                <div class="semester-container">
                    <div class="semester-title">1st Semester</div>
                    @if (!empty($yearTerms['1st_sem']) && count($yearTerms['1st_sem']) > 0)
                        <table class="semester-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Descriptive Title</th>
                                    <th>Units</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($yearTerms['1st_sem'] as $curriculumSubject)
                                    <tr>
                                        <td>{{ $curriculumSubject->subject->subject_code }}</td>
                                        <td>{{ $curriculumSubject->subject->descriptive_title }}</td>
                                        <td>{{ $curriculumSubject->subject->units }}</td>
                                        <td>
                                            @php
                                                $grade = $grades->where('subject_id', $curriculumSubject->subject_id)->first();
                                                $output = $grade->grade ?? $grade->enrolled_term ?? '';
                                            @endphp
                                            {{ $output }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="text-align:center; font-style:italic;">No data available for 1st Semester.</p>
                    @endif
                </div>

                {{-- 2nd Semester Table --}}
                <div class="semester-container">
                    <div class="semester-title">2nd Semester</div>
                    @if (!empty($yearTerms['2nd_sem']) && count($yearTerms['2nd_sem']) > 0)
                        <table class="semester-table">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Descriptive Title</th>
                                    <th>Units</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($yearTerms['2nd_sem'] as $curriculumSubject)
                                    <tr>
                                        <td>{{ $curriculumSubject->subject->subject_code }}</td>
                                        <td>{{ $curriculumSubject->subject->descriptive_title }}</td>
                                        <td>{{ $curriculumSubject->subject->units }}</td>
                                        <td>
                                            @php
                                                $grade = $grades->where('subject_id', $curriculumSubject->subject_id)->first();
                                                $output = '';
                                                if ($grade) {
                                                    // Check if the grade is 'Enrolled'
                                                    if ($grade->grade === 'Enrolled') {
                                                        $output = $grade->enrolled_term; // Show enrolled_term if grade is 'Enrolled'
                                                    } else {
                                                        // Show the grade or other logic if not 'Enrolled'
                                                        $output = $grade->grade;
                                                    }
                                                }
                                            @endphp
                                            {{ $output }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p style="text-align:center; font-style:italic;">No data available for 2nd Semester.</p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="footer">
    <p>Generated on {{ now()->format('F j, Y') }}</p>
</div>

</body>
</html>
