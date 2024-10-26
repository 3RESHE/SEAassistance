@include('evaluator.css')
@include('evaluator.sidebar')

<div class="new-container">
    <div class="new-card">
        <div class="new-card-header">
            <h3 class="new-card-title">Evaluation of Grades Request</h3>
        </div>
        <div class="new-card-body new-table-responsive">
            <table class="new-table">
                <thead>
                    <tr>
                        <th>Select</th> <!-- Checkbox header -->
                        <th>Student Name</th>
                        <th>School ID</th>
                        <th>Status</th>
                        <th>Action</th> <!-- Action column for individual print -->
                    </tr>
                </thead>
                <tbody>
                    @foreach($torRequests as $request)
                        <tr>
                            <td>
                                <input type="checkbox" class="new-select-checkbox" value="{{ $request->id }}">
                            </td>
                            <td>{{ $request->user->name }}</td>
                            <td>{{ $request->user->school_id }}</td>
                            <td>
                                <span class="new-status-badge {{ strtolower($request->status) }}">
                                    {{ $request->status }}
                                </span>
                            </td>
                            <td>
                                <button class="new-print-button" onclick="printSingle({{ $request->id }})">Print PDF</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="new-action-buttons">
                <button class="new-print-button" onclick="printSelected()">Print Selected</button>
            </div>
        </div>
    </div>
</div>

<script src="evaluator/main.js"></script>
@include('evaluator.footer')

<script>
function printSingle(id) {
    window.open(`/print-tor/${id}`, '_blank'); // Open the PDF in a new tab
}


    function printSelected() {
        const checkboxes = document.querySelectorAll('.new-select-checkbox:checked');
        const selectedIds = Array.from(checkboxes).map(checkbox => checkbox.value);

        if (selectedIds.length === 0) {
            alert("Please select at least one record to print.");
            return;
        }

        // Logic to print multiple selected IDs
        console.log("Selected IDs for printing:", selectedIds);
        alert("Selected IDs: " + selectedIds.join(', '));
        // Implement your actual printing logic here
    }
</script>

<style>
    /* Container styling */
    .new-container {
        display: flex;
        justify-content: center; /* Center horizontally */
        margin-top: 50px; /* Space from top */
        padding: 20px; /* Padding around the container */
        background-color: #f8f9fa; /* Light background for contrast */
    }

    /* Card styling */
    .new-card {
        width: 100%; /* Full width */
        max-width: 900px; /* Limit max width */
        background-color: #fff; /* White background */
        border: 1px solid #e0e0e0; /* Light border */
        border-radius: 8px; /* Rounded corners */
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        overflow: hidden; /* Prevent overflow */
    }

    /* Card header styling */
    .new-card-header {
        background-color: #007bff; /* Blue background */
        color: white; /* White text */
        padding: 15px; /* Padding for header */
        text-align: center; /* Center text */
    }

    .new-card-title {
        margin: 0; /* Remove default margin */
        font-size: 1.5rem; /* Larger title size */
        font-weight: 600; /* Bold title */
    }

    /* Table styling */
    .new-table {
        width: 100%; /* Full width */
        border-collapse: collapse; /* Collapse borders */
        margin-top: 10px; /* Space above the table */
    }

    .new-table th, 
    .new-table td {
        padding: 10px; /* Padding inside cells */
        border-bottom: 1px solid #ddd; /* Light bottom border */
        text-align: left; /* Left-align text */
    }

    /* Checkbox styling */
    .new-select-checkbox {
        width: 20px; /* Set checkbox size */
        height: 20px; /* Set checkbox size */
    }

    /* Status badge styling */
    .new-status-badge {
        padding: 5px 10px; /* Padding around badge text */
        border-radius: 5px; /* Rounded badge corners */
        color: white; /* White text for badge */
        font-weight: 500; /* Semi-bold text */
    }

    .new-status-badge.pending {
        background-color: #f0ad4e; /* Yellow for pending */
    }

    .new-status-badge.approved {
        background-color: #5cb85c; /* Green for approved */
    }

    .new-status-badge.rejected {
        background-color: #d9534f; /* Red for rejected */
    }

    /* Action button styling */
    .new-action-buttons {
        margin-top: 15px; /* Margin above the button */
        text-align: right; /* Align buttons to the right */
    }

    .new-print-button {
        background-color: #007bff; /* Button background color */
        color: white; /* White text */
        border: none; /* No border */
        border-radius: 5px; /* Rounded corners */
        padding: 10px 15px; /* Padding for button */
        cursor: pointer; /* Pointer cursor */
        font-size: 16px; /* Button text size */
    }

    .new-print-button:hover {
        background-color: #0056b3; /* Darker blue on hover */
    }

    /* Mobile responsiveness */
    @media (max-width: 600px) {
        .new-table th, .new-table td {
            display: block; /* Block display on small screens */
            text-align: left; /* Left-align text */
        }

        .new-card-header {
            text-align: center; /* Center header text */
        }

        .new-print-button {
            width: 100%; /* Full width button on small screens */
            margin-top: 10px; /* Space above button */
        }
    }
</style>
