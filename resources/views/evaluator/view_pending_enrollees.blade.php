@include('secretary.css')
@include('evaluator.sidebar')

<main>
    <section class="content-box">
        
        <div class="card1">
            <div class="card-header">
                <h2>Advising Records</h2>
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
                                        <a href="{{ url('enrollee_advising_details', $advising->id) }}" class="btn-view-details">View Details</a>
                                        <form action="{{ route('EnrollStudent', $advising->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" style="background-color: green; border: none; color: white; cursor: pointer;" class="btn-view-details">
                                                Enroll
                                            </button>
                                        </form>
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
