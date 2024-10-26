@include('evaluator.css')
@include('evaluator.sidebar')

<style>
    /* Dropdown Styles */
.grade-dropdown {
    display: block;
    width: 100%;
    padding: 0.5rem;
    border: 1px solid #ccc;
    border-radius: 0.375rem; /* Rounded corners */
    box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    font-size: 1rem;
    color: #333;
    appearance: none;
    -webkit-appearance: none; /* For Safari */
    -moz-appearance: none; /* For Firefox */
}

.grade-dropdown:focus {
    border-color: #4f46e5; /* Indigo color */
    outline: none;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2); /* Focus shadow */
}

/* Button Styles */
.save-button {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.375rem; /* Rounded corners */
    background-color: #4f46e5; /* Indigo color */
    color: #fff;
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.save-button:hover {
    background-color: #4338ca; /* Darker indigo for hover */
}

.save-button:focus {
    outline: 2px solid #4f46e5; /* Indigo focus outline */
    outline-offset: 2px;
}

</style>

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <!-- Curriculum Details -->
        <div class="curriculum-container">
            <h2 class="curriculum-title">
                <span style="font-family: 'Times New Roman', Times, serif">Program:</span> 
                {{ $user->course->course_name }}
            </h2>
            <br>
            <h2 style="text-align: left" class="curriculum-title">
                <span style="font-family: 'Times New Roman', Times, serif">Name: </span> 
                {{ $user->last_name }} , {{ $user->name }} {{ $user->middle_name }}.
            </h2>
            <h2 style="text-align: left" class="curriculum-title">
                <span style="font-family: 'Times New Roman', Times, serif">School ID: </span> 
                {{ $user->school_id }}
            </h2>

            <div class="curriculum-form__table-container">
                <form action="{{url('update_grade')}}" method="POST">
                    @csrf
                    <table class="curriculum-form__table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year Term</th>
                                <th>Subject Code</th>
                                <th>Descriptive Title</th>
                                <th>Units</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $previousGroup = '';
                                $index = 1;
                                $currentGroupTotal = 0;
                            @endphp

                            @foreach($subjects as $group => $subjectsByTerm)
                                @foreach($subjectsByTerm as $subject)
                                    @if($previousGroup != $group)
                                        @if($previousGroup != '')
                                            <tr>
                                                <td colspan="4" style="text-align: right; font-weight: bold; background-color: #f0f0f0;">
                                                    Total Units for {{ $previousGroup }}
                                                </td>
                                                <td style="font-weight: bold; background-color: #f0f0f0;">
                                                    {{ $currentGroupTotal }}
                                                </td>
                                                <td></td>
                                            </tr>
                                        @endif

                                        @php
                                            $currentGroupTotal = 0;
                                            $previousGroup = $group;
                                        @endphp
                                    @endif

                                    <tr>
                                        <td>{{ $index++ }}</td>
                                        <td>{{ $subject->year }} - {{ $subject->year_term }}</td>
                                        <td>{{ $subject->subject_code }}</td>
                                        <td>{{ $subject->descriptive_title }}</td>
                                        <td>{{ $subject->units }}</td>
                                        <td>
                                            <select name="grades[{{ $subject->id }}]" class="grade-dropdown" {{ isset($grades[$subject->id]) ? 'disabled' : '' }}>
                                                @if(isset($grades[$subject->id]))
                                                    <!-- Display current grade -->
                                                    <option value="{{ $grades[$subject->id] }}">{{ $grades[$subject->id] }}</option>
                                                @else
                                                    <!-- Default options -->
                                                    <option value="" disabled selected>Select grade</option>
                                                    <option value="1.0">1.0</option>
                                                    <option value="1.25">1.25</option>
                                                    <option value="1.50">1.50</option>
                                                    <option value="1.75">1.75</option>
                                                    <option value="2.0">2.0</option>
                                                    <option value="2.25">2.25</option>
                                                    <option value="2.50">2.50</option>
                                                    <option value="3.0">3.0</option>
                                                    <option value="5.0">5.0</option>
                                                    <option value="W/F">W/F - Withdrawn/Failure</option>
                                                    <option value="W">W - Withdrawn</option>
                                                    <option value="UD">UD - Unofficially Dropped</option>
                                                    <option value="OD">OD - Officially Dropped</option>
                                                    <option value="INC">INC - Incomplete</option>
                                                @endif
                                            </select>
                                        </td>
                                        
                                    </tr>
                                    
                                    @php
                                        $currentGroupTotal += $subject->units;
                                    @endphp
                                @endforeach
                            @endforeach

                            <!-- Display total for the last group -->
                            <tr>
                                <td colspan="4" style="text-align: right; font-weight: bold; background-color: #f0f0f0;">
                                    Total Units for {{ $previousGroup }}
                                </td>
                                <td style="font-weight: bold; background-color: #f0f0f0;">
                                    {{ $currentGroupTotal }}
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="submit" class="save-button">Save Grades</button>

                    
                </form>
            </div>
        </div>

        <br><br><br><br>
        
 
        <!--========== MAIN JS ==========-->
        <script src="{{ asset('evaluator/main.js') }}"></script>
    </section>
</main>


@include('evaluator.footer')