@include('secretary.css')
@include('evaluator.sidebar')

<main>
    <section class="content-box">
        
        <div class="card1">
            <div class="card-header">
                <h2>Enrolled Student</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="advising-table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Surname</th>
                                <th>First Name</th>
                                <th>Middle Initial</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advisings as $advising)
                                <tr>
                                    <td>{{ $advising->user->school_id }}</td>
                                    <td>{{ $advising->user->last_name }}</td>
                                    <td>{{ $advising->user->name }}</td>
                                    <td>{{ $advising->user->middle_name }}</td>
                                    <td>{{ $advising->advising_status }}</td>
                         
                                    <td>
                                        <a href="{{ url('enrolled_student_details', $advising->id) }}" class="btn-view-details">View Details</a>
                                    </td>
                                    
                                 
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>


@include('evaluator.footer')
<script src="evaluator/main.js"></script>
