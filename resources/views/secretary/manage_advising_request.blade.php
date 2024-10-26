@include('secretary.css')
@include('secretary.sidebar')

<main>
    <section class="content-box">
        <div class="card1">
            <div class="card-header">
                <h2>Advising Request</h2>
            </div>
            <div class="card-body">
                <div class="table-container">
                    @if($advisings->isEmpty())
                        <p>No advising requests at the moment.</p>
                    @else
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
                                            @if($advising->advising_status == 'Cancelled')
                                                <a href="{{ url('cancelled_advising_details', $advising->id) }}" class="btn-view-details">View Details</a>
                                            @elseif($advising->advising_status == 'Processing')
                                                <a href="{{ url('advising_details', $advising->id) }}" class="btn-view-details">View Details</a>
                                                <form action="{{ route('cancelAdvisingDetails', $advising->id) }}" method="POST" class="cancel-advising-form" data-school-id="{{ $advising->user->school_id }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" style="background-color: red; border: none; color: white; cursor: pointer;" class="btn-view-details">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @elseif($advising->advising_status == 'Approved')
                                                <a href="{{ url('advising_details', $advising->id) }}" class="btn-view-details">Edit</a>
                                                <form action="{{ route('cancelAdvisingDetails', $advising->id) }}" method="POST" class="cancel-advising-form" data-school-id="{{ $advising->user->school_id }}" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" style="background-color: red; border: none; color: white; cursor: pointer;" class="btn-view-details">
                                                        Cancel
                                                    </button>
                                                </form>
                                            @elseif($advising->advising_status == 'Enrolled')
                                                <a href="{{ url('enrolled_advising_details', $advising->id) }}" class="btn-view-details">View Details</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </section>
</main>

@include('secretary.footer')
<script src="secretary/main.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const cancelForms = document.querySelectorAll('.cancel-advising-form');

        cancelForms.forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent immediate submission

                const schoolId = form.dataset.schoolId; // Get the school ID

                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to cancel the advising request for Student ID: ${schoolId}. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, cancel it'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Proceed with form submission
                    }
                });
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
