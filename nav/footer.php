<!-- ADD CONCERN MODAL -->
<div class="modal fade" id="addConcern">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5>Add Concern</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../page/kagawad.php?function=addConcern" method="POST">
                <div class="modal-body">
                    <input type="text" name="concern_name" class="form-control" placeholder="Concern Description">
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- TERM MODAL -->
<div class="modal fade" id="addTerm">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5>Add Term</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../page/captain.php?function=addTerm" method="POST">
                <div class="modal-body">
                    <label>Start Year</label>
                    <input type="date" name="start_year" class="form-control mb-2" required>

                    <label>End Year</label>
                    <input type="date" name="end_year" class="form-control mb-2" required>
                </div>
                <div class="modal-footer">
                    <button name="add" class="btn btn-warning">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- RESIDENT MODAL -->
<div class="modal fade" id="addModel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5>Add Resident</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../page/secretary.php?function=addResident" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Middle Name</label>
                            <input type="text" name="middle_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Birthdate</label>
                            <input type="date" name="birthdate" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Gender</label>
                            <input type="text" name="gender" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Civil Status</label>
                            <input type="text" name="civil_status" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Contact No.</label>
                            <input type="text" name="contact_number" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Voter Status</label>
                            <input type="text" name="voter_status" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Occupation</label>
                            <input type="text" name="occupation" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button name="add" class="btn btn-danger">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ADD MODAL -->
<div class="modal fade" id="addModals">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5>Add Financial Record</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="date" name="date" class="form-control mb-2" required>
                    <select name="type" class="form-control mb-2">
                        <option>Income</option>
                        <option>Expense</option>
                    </select>
                    <input type="text" name="description" class="form-control mb-2" placeholder="Description" required>

                    <input type="number" name="amount" class="form-control mb-2" placeholder="Amount" required>

                    <select name="status" class="form-control">
                        <option>Completed</option>
                        <option>Pending</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button name="add" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BLOTTTER MODAL -->
<div class="modal fade" id="addModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5>Add Blotter Record</h5>
                <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="../page/secretary.php?function=addBlotter" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Date</label>
                            <input type="date" name="bltr_incident_date" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Time</label>
                            <input type="time" name="bltr_incident_time" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label>Location</label>
                            <input type="text" name="bltr_incident_location" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Complainant Name</label>
                            <input type="text" name="bltr_compl_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Complainant Age</label>
                            <input type="number" name="bltr_compl_age" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Complainant Address</label>
                            <input type="text" name="bltr_compl_address" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Respondent Name</label>
                            <input type="text" name="bltr_resp_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Respondent Age</label>
                            <input type="text" name="bltr_resp_age" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Respondent Address</label>
                            <input type="text" name="bltr_resp_address" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Incident Type</label>
                            <input type="text" name="incident_type" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label>Action Taken</label>
                            <input type="text" name="action_taken" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button name="add" class="btn btn-danger">Save Record</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</script>

<script>
    // Password confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordMatch = document.getElementById('passwordMatch');
        const submitBtn = document.getElementById('submitBtn');

        function validatePassword() {
            const pw = password.value;
            const confirmPw = confirmPassword.value;
            const matchDiv = passwordMatch;

            if (confirmPw === '') {
                matchDiv.textContent = '';
                matchDiv.style.color = '';
                return;
            }

            if (pw === confirmPw && pw.length >= 6) {
                matchDiv.textContent = '✓ Passwords match!';
                matchDiv.style.color = '#198754';
                submitBtn.disabled = false;
            } else {
                if (pw !== confirmPw) {
                    matchDiv.textContent = '✗ Passwords do not match!';
                } else {
                    matchDiv.textContent = '✗ Password too short (min 6 chars)';
                }
                matchDiv.style.color = '#dc3545';
                submitBtn.disabled = true;
            }
        }

        password.addEventListener('input', validatePassword);
        confirmPassword.addEventListener('input', validatePassword);
    });
</script>
<script>
    setTimeout(() => {
        let alert = document.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            alert.classList.add('fade');
        }
    }, 30000000);
</script>
<script>
    function printDiv(divId) {
        var content = document.getElementById(divId).innerHTML;
        var myWindow = window.open('', '', 'width=800,height=600');

        myWindow.document.write('<html><head><title>Print</title>');
        myWindow.document.write('</head><body>');
        myWindow.document.write(content);
        myWindow.document.write('</body></html>');

        myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close();
    }
</script>
<script>
    // modal blotter 
    // Open modal
    const openModalBtn = document.getElementById('openModal');
    const modal = document.getElementById('blotterModal');
    const closeModalBtn = document.getElementById('closeModal');

    openModalBtn.addEventListener('click', () => {
        modal.style.display = 'flex';
    });

    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
<!-- SEARCH SCRIPT -->
<script>
    //search
    document.getElementById("search").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#residentTable tbody tr");

        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
<script>
    // Handle form submission
    const blotterForm = document.getElementById('blotterForm');
    blotterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Blotter record saved!'); // Replace this with actual save logic
        modal.style.display = 'none';
        blotterForm.reset();
    });

    document.getElementById("search").addEventListener("keyup", function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#residentTable tbody tr");

        rows.forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
        });
    });
</script>
</body>

</html>