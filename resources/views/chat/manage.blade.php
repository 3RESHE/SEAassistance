@include('admin.css')
@include('admin.sidebar', ['user' => Auth::user()])

<main>

    <section>
        <div class="containerCHAT">
            <h1 class="titleCHAT">Manage Chat Support</h1>

            <!-- Form to Add Keyword -->
            <h3 class="subtitleCHAT">Add Keyword</h3>
            <form action="{{ route('chat.storeKeyword') }}" method="POST" class="formCHAT">
                @csrf
                <input type="text" name="keyword" placeholder="Enter keyword" class="inputCHAT" required>
                <button type="submit" class="buttonCHAT">Add Keyword</button>
            </form>

            <!-- Form to Add Question -->
            <h3 class="subtitleCHAT">Add Question</h3>
            <form action="{{ route('chat.storeQuestion') }}" method="POST" class="formCHAT">
                @csrf
                <select name="keyword_id" class="inputCHAT" required>
                    @foreach($keywords as $keyword)
                        <option value="{{ $keyword->id }}">{{ $keyword->keyword }}</option>
                    @endforeach
                </select>
                <input type="text" name="question" placeholder="Enter question" class="inputCHAT" required>
                <textarea name="answer" placeholder="Enter answer" class="inputCHAT" required></textarea>
                <button type="submit" class="buttonCHAT">Add Question</button>
            </form>

            <!-- Table of Keywords with Edit and Delete Options -->
            <h3 class="subtitleCHAT">Keywords</h3>
            <div class="tableWrapperCHAT">
                <table class="tableCHAT">
                    <thead>
                        <tr>
                            <th>Keyword</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($keywords as $keyword)
                            <tr>
                                <td>{{ $keyword->keyword }}</td>
                                <td>
                                    <!-- Edit Keyword Button -->
                                    <button class="buttonEditCHAT" onclick="editKeyword({{ $keyword->id }}, '{{ $keyword->keyword }}')">Edit</button>

                                    <!-- Delete Keyword Form -->
                                    <form action="{{ route('chat.deleteKeyword', $keyword->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="buttonDeleteCHAT">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Table of Questions with Edit and Delete Options -->
            <h3 class="subtitleCHAT">Questions</h3>
            <div class="tableWrapperCHAT">
                <table class="tableCHAT">
                    <thead>
                        <tr>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Keyword</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($questions as $question)
                            <tr>
                                <td>{{ $question->question }}</td>
                                <td>{{ $question->answer }}</td>
                                <td>{{ $question->keyword->keyword }}</td>
                                <td>
                                    <!-- Edit Question Button -->
                                    <button class="buttonEditCHAT" onclick="editQuestion({{ $question->id }}, '{{ $question->question }}', '{{ $question->answer }}', {{ $question->keyword_id }})">Edit</button>

                                    <!-- Delete Question Form -->
                                    <form action="{{ route('chat.deleteQuestion', $question->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="buttonDeleteCHAT">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modal for Editing Keyword -->
            <div id="editKeywordModal" class="modalCHAT" style="display:none;">
                <h3 class="modalTitleCHAT">Edit Keyword</h3>
                <form id="editKeywordForm" method="POST" class="formCHAT">
                    @csrf
                    @method('PUT')
                    <input type="text" id="editKeywordInput" name="keyword" class="inputCHAT" required>
                    <button type="submit" class="buttonCHAT">Update Keyword</button>
                    <button type="button" class="buttonCloseCHAT" onclick="closeModal('editKeywordModal')">Close</button>
                </form>
            </div>

            <!-- Modal for Editing Question -->
            <div id="editQuestionModal" class="modalCHAT" style="display:none;">
                <h3 class="modalTitleCHAT">Edit Question</h3>
                <form id="editQuestionForm" method="POST" class="formCHAT">
                    @csrf
                    @method('PUT')
                    <select id="editKeywordSelect" name="keyword_id" class="inputCHAT" required>
                        @foreach($keywords as $keyword)
                            <option value="{{ $keyword->id }}">{{ $keyword->keyword }}</option>
                        @endforeach
                    </select>
                    <input type="text" id="editQuestionInput" name="question" class="inputCHAT" required>
                    <textarea id="editAnswerInput" name="answer" class="inputCHAT" required></textarea>
                    <button type="submit" class="buttonCHAT">Update Question</button>
                    <button type="button" class="buttonCloseCHAT" onclick="closeModal('editQuestionModal')">Close</button>
                </form>
            </div>
        </div>

        <script>
            // Function to open the Edit Keyword Modal with the keyword pre-filled
            function editKeyword(id, keyword) {
                document.getElementById('editKeywordInput').value = keyword;
                document.getElementById('editKeywordForm').action = '/chat/keyword/update/' + id;
                document.getElementById('editKeywordModal').style.display = 'block';
            }

            // Function to open the Edit Question Modal with the question and answer pre-filled
            function editQuestion(id, question, answer, keywordId) {
                document.getElementById('editQuestionInput').value = question;
                document.getElementById('editAnswerInput').value = answer;
                document.getElementById('editKeywordSelect').value = keywordId;
                document.getElementById('editQuestionForm').action = '/chat/question/update/' + id;
                document.getElementById('editQuestionModal').style.display = 'block';
            }

            // Function to close modals
            function closeModal(modalId) {
                document.getElementById(modalId).style.display = 'none';
            }
        </script>
    </section>

</main>
<br>
<!--========== MAIN JS ==========-->
<script src="admin/main.js"></script>
@include('admin.footer')


<style>
    .containerCHAT {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f7f7f7;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.titleCHAT {
    font-size: 26px;
    font-weight: bold;
    margin-bottom: 20px;
    text-align: center;
}

.subtitleCHAT {
    font-size: 20px;
    margin-top: 20px;
    margin-bottom: 10px;
}

.formCHAT {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    gap: 10px;
}

.inputCHAT {
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    width: 100%;
}

.buttonCHAT {
    background-color: #6c757d; /* Grey */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.buttonCHAT:hover {
    background-color: #5a6268; /* Darker grey */
}

.buttonEditCHAT {
    background-color: #28a745; /* Green */
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.buttonEditCHAT:hover {
    background-color: #218838; /* Darker green */
}

.buttonDeleteCHAT {
    background-color: #dc3545; /* Red */
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.buttonDeleteCHAT:hover {
    background-color: #c82333; /* Darker red */
}

.buttonCloseCHAT {
    background-color: #ffc107; /* Yellow */
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.buttonCloseCHAT:hover {
    background-color: #e0a800; /* Darker yellow */
}

.tableWrapperCHAT {
    overflow-x: auto;
    margin-top: 20px;
}

.tableCHAT {
    width: 100%;
    border-collapse: collapse;
}

.tableCHAT th,
.tableCHAT td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

.tableCHAT th {
    background-color: #343a40; /* Dark grey */
    color: white;
}

.tableCHAT tr:nth-child(even) {
    background-color: #f2f2f2;
}

.modalCHAT {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    z-index: 1000;
    display: none;
}

.modalTitleCHAT {
    font-size: 22px;
    margin-bottom: 15px;
}

</style>