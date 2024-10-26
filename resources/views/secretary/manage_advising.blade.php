@include('secretary.css')
@include('secretary.sidebar')

<!--========== CONTENTS ==========-->
<br>
<main>
    <section>
        <!-- Courses -->
        <div class="curriculum-container">
            <h2 class="curriculum-title">Manage Advising</h2>
            <form action="{{ url('updateAdvising') }}" method="POST" class="curriculum-form">
                @csrf
                <div class="curriculum-form__table-container">
                    <table class="curriculum-form__table">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Semester</th>
                                <th>Descriptive Title</th>
                                <th>Course Code</th>
                                <th>Units</th>
                                <th>Prerequisite Subjects</th>
                                <th>Corequisite Subjects</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($subjects as $subject)
                            <tr>
                                <td>
                                    <select name="year[]" class="curriculum-form__select">
                                        <option value="1st" {{ $subject->year == '1st' ? 'selected' : '' }}>1st Year</option>
                                        <option value="2nd" {{ $subject->year == '2nd' ? 'selected' : '' }}>2nd Year</option>
                                        <option value="3rd" {{ $subject->year == '3rd' ? 'selected' : '' }}>3rd Year</option>
                                        <option value="4th" {{ $subject->year == '4th' ? 'selected' : '' }}>4th Year</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="year_term[]" class="curriculum-form__select">
                                        <option value="1st_sem" {{ $subject->year_term == '1st_sem' ? 'selected' : '' }}>1st Sem</option>
                                        <option value="2nd_sem" {{ $subject->year_term == '2nd_sem' ? 'selected' : '' }}>2nd Sem</option>
                                        <option value="summer" {{ $subject->year_term == 'summer' ? 'selected' : '' }}>Summer</option>
                                    
                                    </select>
                                </td>
                                <td>
                                   {{ $subject->descriptive_title }}
                                </td>
                                <td>
                                    {{ $subject->subject_code }}
                                </td>
                                <td>
                                    {{ $subject->units }}
                                </td>
                                <td>
                                    @if ($subject->prerequisites->isNotEmpty())
                                        {{ $subject->prerequisites->pluck('descriptive_title')->join(', ') }}
                                    @else
                                        No Prerequisites
                                    @endif
                                </td>
                                <td>
                                    @if ($subject->corequisites->isNotEmpty())
                                        {{ $subject->corequisites->pluck('descriptive_title')->join(', ') }}
                                    @else
                                        No Corequisites
                                    @endif
                                </td>
                                <input type="hidden" name="subject_ids[]" value="{{ $subject->id }}">
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="curriculum-form__submit">Update Subjects</button>
            </form>
        </div>


        

        <!--========== MAIN JS ==========-->
        <script src="{{ asset('secretary/main.js') }}"></script>
    </section>
</main>


@include('secretary.footer')