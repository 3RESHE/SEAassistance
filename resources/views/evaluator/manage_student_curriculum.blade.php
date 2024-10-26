@include('evaluator.sidebar')
@include('evaluator.css')

<!-- Custom CSS -->
<style>
    .card-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-bottom: 1px solid #ddd;
        border-radius: 8px 8px 0 0;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
    }

    .form-control {
        width: 100%;
        padding: 8px 12px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }

    .form-control[readonly] {
        background-color: #e9ecef;
    }

    .form-control:focus {
        outline: none;
        border-color: #66afe9;
        box-shadow: 0 0 8px rgba(102, 175, 233, 0.6);
    }

    .btn {
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    @media (max-width: 768px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<br>
<main>
    <section>
        <div class="container">
            <div class="card">
                <div class="card-header">Manage Student</div>
                <div class="card-body">
                    <form action="{{ url('give_student_curriculum', $user->id) }}" method="POST">
                        @csrf
                        <div class="grid">
                            <!-- Program -->
                            <div class="form-group">
                                <label for="program">Program:</label>
                                <input type="text" id="program" name="program" class="form-control" value="Bachelor of Science in Information Technology" readonly>
                            </div>

                            <!-- First Name -->
                            <div class="form-group">
                                <label for="first_name">First Name:</label>
                                <input type="text" id="first_name" name="first_name" class="form-control" value="{{ $user->name }}" readonly>
                            </div>

                            <!-- Last Name -->
                            <div class="form-group">
                                <label for="last_name">Last Name:</label>
                                <input type="text" id="last_name" name="last_name" class="form-control" value="{{ $user->last_name }}" readonly>
                            </div>

                            <!-- Middle Name -->
                            <div class="form-group">
                                <label for="middle_name">Middle Name:</label>
                                <input type="text" id="middle_name" name="middle_name" class="form-control" value="{{ $user->middle_name }}" readonly>
                            </div>

                            <!-- School ID -->
                            <div class="form-group">
                                <label for="school_id">School ID:</label>
                                <input type="text" id="school_id" name="school_id" class="form-control" value="{{ $user->school_id }}" readonly>
                            </div>

                            <!-- Curriculum -->
                            <div class="form-group">
                                <label for="curriculum_id">Curriculum:</label>
                                <select id="curriculum_id" name="curriculum_id" class="form-control">
                                    @foreach($curriculums as $curriculum)
                                        <option value="{{ $curriculum->id }}">{{ $curriculum->curriculum_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            

                            <div class="form-group">
                                <label for="semester">Semester:</label>
                                <select name="semester" id="semester" class="form__input" disabled>
                                    <option value="{{ $user->semester }}" selected>{{ $user->semester }}</option>
                                    <option value="1st_sem">1st Semester</option>
                                    <option value="2nd_sem">2nd Semester</option>
                                    <option value="summer">Summer</option>
                                </select>
                            </div>
                            
                            <input type="hidden" name="semester" value="{{ $user->semester }}">



                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ url('/manage_student_details', $user->id) }}';">
                                Back
                            </button>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

@include('evaluator.footer')
<!--========== MAIN JS ==========-->
<script src="secretary/main.js"></script>
