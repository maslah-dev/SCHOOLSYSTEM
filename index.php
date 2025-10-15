<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>school Management System</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎓 School Management System</h1>
            <p>Comprehensive Education Portal</p>
        </div>
        <div class="content">
            <div id="homePage" class="page active">
                <div class="role-selector">
                    <div class="role-card student" onclick="showPage('studentLogin')">
                        <div class="role-icon">👨‍🎓</div>
                        <h3>Student Portal</h3>
                        <p>Access your profile, results, and fees information</p>
                    </div>

                    <div class="role-card parent" onclick="showPage('parentLogin')">
                        <div class="role-icon">👨‍👩‍👦</div>
                        <h3>Parent Portal</h3>
                        <p>Track your child's progress and behavior</p>
                    </div>

                    <div class="role-card teacher" onclick="showPage('teacherLogin')">
                        <div class="role-icon">👨‍🏫</div>
                        <h3>Teacher Portal</h3>
                        <p>Manage students and record daily behavior</p>
                    </div>
                </div>
            </div>


            <div id="studentLogin" class="page">
                <button class="back-btn" onclick="showPage('homePage')">⬅️ Back to Home</button>
                <h2 style="margin-bottom: 25px;">student Login</h2>
                <div class="demo-Credentials">
                    <h4>🔑 Demo Credentials</h4>
                    <p><strong>Admission Number:</strong>STU001</p>
                    <p><strong>Password</strong>password123</p>
                </div>

                <div id="studentLoginAlert"></div>
                <form id="studentLoginForm">
                    <div class="form-group">
                        <label for="studentAdmission">Admission Number</label>
                        <input type="text" id="studentAdmission" required placeholder="e.g., STU001">
                    </div>
                    <div class="form-group">
                        <label for="studentPassword">Password</label>
                        <input type="password" id="studentPassword" required placeholder="Enter your password">
                    </div>
                    <button type="submit" class="btn">Login</button>

                </form>

                <button class="link-btn" onclick="showPage('studentRegister')">Don't have an account? Register here</button>
            </div>

            <div id="studentRegister" class="page">
                <button class="back-btn" onclick="showPage('studentLogin')">⬅️ Back to Login</button>
   
                <h2 style="margin-bottom: 25px;">Student Registration</h2>
                <div id="studentRegAlert"></div>

                <form id="studentRegisterForm">
                    <div class="two-column">
                        <div class="form-group">
                            <label for="regStudentAdmission">Admission Number *</label>
                            <input type="text" id="regStudentAdmission" required placeholder="e.g., STU002">
                        </div>

                        <div class="form-group">
                            <label for="regStudentName">Full Name *</label>
                            <input type="text" id="regStudentName" required placeholder="Enter full name">
                        </div>

                        <div class="form-group">
                            <label for="regStudentDOB">Date of Birth *</label>
                            <input type="date" id="regStudentDOB" required>
                        </div>

                         <div class="form-group">
                            <label for="regStudentEnrollment">Date of Enrollment *</label>
                            <input type="date" id="regStudentEnrollment" required>
                        </div>

                         <div class="form-group">
                            <label for="regParentName">Parent/Gurdian Name *</label>
                            <input type="text" id="regParentName" required placeholder="Parent full name">
                        </div>
                         <div class="form-group">
                            <label for="regParentPhone">Parent Phone *</label>
                            <input type="tel" id="regParentPhone" required placeholder="+254...">
                        </div>

                         <div class="form-group">
                            <label for="regParentEmail">Parent Email *</label>
                            <input type="email" id="regParentEmail" required placeholder="parent@example.com">
                        </div>

                         <div class="form-group">
                            <label for="regStudentPassword">password</label>
                            <input type="password" id="regStudentPassword" required placeholder="Create password">
                        </div>
                    </div>

                    <button type="Submit" class="btn">Register</button>
                    <button type="button" class="btn btn-secondary" onclick="showPage('studentLogin')">Back to Login</button>
                </form>
            </div>

            <div id="studentDashboard" class="page">
                <div class="dashboard-header">
                    <h2>Welcome, <span id="studentDashName">Student</span>!</h2>
                    <button class="logout-btn" onclick="logoutStudent()">Logout</button>
                </div>

                <div>
                    <button class="nav-btn active" onclick="showStudentSection('studentProfile')">Profile</button>
                    <button class="nav-btn active" onclick="showStudentSection('studentResults')">Results</button>
                    <button class="nav-btn active" onclick="showStudentSection('studentFees')">Fees Status</button>
                    <button class="nav-btn active" onclick="showStudentSection('studentBehavior')">My Behavior</button>
                </div>

                <div id="studentProfile" class="section active">
                    <h3 style="margin-bottom: 20px;">Student Information</h3>
                    <div class="info-card">
                        <div class="info-row">
                            <span class="info-label">Full Name:</span>
                            <span class="info-value" id="spName">Loading...</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Admission Number:</span>
                            <span class="info-value" id="spAdmission">Loading...</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-value" id="spDOB">Loading...</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Date of Enrollment:</span>
                            <span class="info-value" id="spEnrollment">Loading...</span>
                        </div>
                    </div>

                    <h3 style="margin: 30px 0 20px 0;">Parent/Guardian Information</h3>
                    <div class="info-card">
                        <div class="info-row">
                            <span class="info-label">Name:</span>
                            <span class="info-value" id="spParentName">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Phone:</span>
                            <span class="info-value" id="spParentPhone">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value" id="spParentEmail">Loading...</span>
                        </div>
                    </div>
                </div>

                <div id="studentResults" class="section">
                    <h3 style="margin-bottom: 20px;">Academic Results</h3>
                    <div class="table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Term 1</th>
                                    <th>Term 2</th>
                                    <th>Term 3</th>
                                    <th>Average</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody id="studentResultsBody">
                                <tr>
                                    <td colspan="6" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="studentFees" class="section">
                    <h3 style="margin-bottom: 20px;">Fee Payment Status</h3>
                    <div class="info-card">
                        <div class="info-row">
                            <span class="info-label">Total Fees:</span>
                            <span class="info-value" id="sfTotal">Loading...</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Amount Paid:</span>
                            <span class="info-value" id="sfPaid">Loading...</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Balance:</span>
                            <span class="info-value" id="sfBalance">Loading...</span>
                        </div>
                         <div class="info-row">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge" id="sfStatus">Loading...</span>
                            </span>
                        </div>
                    </div>

                    <h4 style="margin: 30px 0 15px 0;">Payment History</h4>
                    <div class="table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Reference</th>
                                </tr>
                            </thead>
                            <tbody id="studentPaymentsBody">
                                <tr>
                                    <td colspan="4" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="studentBehavior" class="section">
                    <h3 style="margin-bottom: 20px;">My Behavior</h3>
                    <div id="studentBehaviorList">Loading...</div>
                </div>
            </div>

            <!-- Parent login (moved out of student area) -->
            <div id="parentLogin" class="page">
                <button class="back-btn" onclick="showPage('homePage')">← Back to Home</button>
                <h2 style="margin-bottom: 25px;">Parent/Guardian Login</h2>
                <div class="demo-Credentials">
                    <h4>🔑Demo Credentials</h4>
                    <p><strong>Phone Number:</strong>+254712345678</p>
                    <p><strong>Password:</strong>password123</p>
                </div>

                <div id="parentLoginAlert"></div>

                <form id="parentLoginForm">
                    <div class="form-group">
                        <label for="parentPhone">Phone Number</label>
                        <input type="tel" id="parentPhone" required placeholder="Enter phone number">
                    </div>
                    <div class="form-group">
                        <label for="parentPassword">Password</label>
                        <input type="password" id="parentPassword" required placeholder="Enter password">
                    </div>

                    <button type="submit" class="btn">Login</button>
                </form>

                <button class="link-btn" onclick="showPage('parentRegister')">Don't have an account? Register here</button>
            </div>

            <div id="studentRegister" class="page">
                <button class="back-btn" onclick="showPage('studentLogin')">⬅️ Back to Login</button>
   
                <h2 style="margin-bottom: 25px;">Student Registration</h2>
                <div id="studentRegAlert"></div>

                <form id="studentRegisterForm">
                    <div class="two-column">
                        <div class="form-group">
                            <label for="regStudentAdmission">Admission Number *</label>
                            <input type="text" id="regStudentAdmission" required placeholder="e.g., STU002">
                        </div>

                        <div class="form-group">
                            <label for="regStudentName">Full Name *</label>
                            <input type="text" id="regStudentName" required placeholder="Enter full name">
                        </div>

                        <div class="form-group">
                            <label for="regStudentDOB">Date of Birth *</label>
                            <input type="date" id="regStudentDOB" required>
                        </div>

                         <div class="form-group">
                            <label for="regStudentEnrollment">Date of Enrollment *</label>
                            <input type="date" id="regStudentEnrollment" required>
                        </div>

                         <div class="form-group">
                            <label for="regParentName">Parent/Gurdian Name *</label>
                            <input type="text" id="regParentName" required placeholder="Parent full name">
                        </div>
                         <div class="form-group">
                            <label for="regParentPhone">Parent Phone *</label>
                            <input type="tel" id="regParentPhone" required placeholder="+254...">
                        </div>

                         <div class="form-group">
                            <label for="regParentEmail">Parent Email *</label>
                            <input type="email" id="regParentEmail" required placeholder="parent@example.com">
                        </div>

                         <div class="form-group">
                            <label for="regStudentPassword">password</label>
                            <input type="password" id="regStudentPassword" required placeholder="Create password">
                        </div>
                    </div>

                    <button type="Submit" class="btn">Register</button>
                    <button type="button" class="btn btn-secondary" onclick="showPage('studentLogin')">Back to Login</button>
                </form>
            </div>

            <div id="parentRegister" class="page">
                <button class="back-btn" onclick="showPage('parentLogin')">← Back to Login</button>
                <h2 style="margin-bottom: 25px;">Parent/Guardian Registration</h2>
                <div id="parentRegAlert"></div>

                <form id="parentRegisterForm">
                    <div class="form-group">
                        <label for="regParFullName">Full Name *</label>
                        <input type="text" id="regParFullName" required placeholder="Your full name">
                    </div>
                     <div class="form-group">
                        <label for="regParPhone">Phone Number *</label>
                        <input type="tel" id="regParPhone" required placeholder="+254...">
                    </div>
                     <div class="form-group">
                        <label for="regParEmail">Email *</label>
                        <input type="email" id="regParEmail" required placeholder="your@email.com">
                    </div>
                     <div class="form-group">
                        <label for="regChildAdmission">Student's Admission Number *</label>
                        <input type="text" id="regChildAdmission" required placeholder="e.g., STU001">
                    </div>
                     <div class="form-group">
                        <label for="regParPassword">Password *</label>
                        <input type="password" id="regParPassword" required placeholder="Create password">
                    </div>

                    <button type="submit" class="btn">Register</button>
                    <button type="button" class="btn btn-secondary" onclick="showPage('parentLogin')">Back to Login</button>
                </form>
            </div>


            <div id="parentDashboard" class="page">
                <div class="dashboard-header">
                    <h2>Welcome, <span>Parent</span>!</h2>
                    <button class="logout-btn" onclick="logoutParent()">Logout</button>
                </div>

                <div class="dashboard-nav">
                    <button class="nav-btn active" onclick="showParentSection('parentProfile')">Child's Profile</button>
                    <button class="nav-btn active" onclick="showParentSection('parentResults')">Results</button>
                    <button class="nav-btn active" onclick="showParentSection('parentBehavior')">Behavior Tracking</button>
                </div>

                <div id="parentProfile" class="section active">
                    <h3 style="margin-bottom: 20px;">Your Child's Information</h3>
                    <div class="info-card">
                        <div class="info-row">
                            <span class="info-label">Full Name:</span>
                            <span class="info-value" id="ppName">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Admission Number:</span>
                            <span class="info-value" id="ppAdmission">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Birth:</span>
                            <span class="info-value" id="ppDOB">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Date of Enrollment:</span>
                            <span class="info-value" id="ppEnrollment">Loading...</span>
                        </div>
                    </div>

                    <h3 style="margin: 30px 0 20px 0;">Fee Status</h3>
                    <div class="info-card">
                        <div class="info-row">
                            <span class="info-label">Total Fees:</span>
                            <span class="info-value" id="ppFeesTotal">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Amount Paid:</span>
                            <span class="info-value" id="ppFeesPaid">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Balance:</span>
                            <span class="info-value" id="ppFeesBalance">Loading...</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Status:</span>
                            <span class="info-value">
                                <span class="status-badge" id="ppFeesStatus">Loading...</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div id="parentResults" class="section">
                    <h3 style="margin-bottom: 20px;">Academic Performance</h3>
                    <div clas="table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Term 1</th>
                                    <th>Term 2</th>
                                    <th>Term 3</th>
                                    <th>Average</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody id="parentResultsBody">
                                <tr>
                                    <td colspan="6" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="parentBehavior" class="section">
                    <h3 style="margin-bottom: 20px;">Daily Behavior Records</h3>
                    <p style="margin-bottom: 20px; color: #666;">Track your child's behavior at school everyday.</p>
                    <div id="parentBehaviorList">Loading...</div>
                </div>
            </div>

            <div id="teacherLogin" class="page">
                <button class="back-btn" onclick="showPage('homePage')">← Back to Home</button>
                <h2 style="margin-bottom: 25px;">Teacher Login</h2>

                <div class="demo-Credentials">
                    <h4>🔑 Demo-Credentials</h4>
                    <p><STRONG>Teacher ID:</STRONG>TCH001</p>
                    <p><strong>Password:</strong>password123</p>
                </div>

                <div id="teacherLoginAlert"> </div>
                    <form id="teacherLoginForm">
                        <div class="form-group">
                            <label for="teacherEmail">Teacher Email / ID</label>
                            <input type="text" id="teacherEmail" required placeholder="Enter teacher email or ID">
                        </div>
                        <div class="form-group">
                            <label for="teacherPassword">Password</label>
                            <input type="password" id="teacherPassword" required placeholder="Enter password">
                        </div>
                        <button type="submit" class="btn">Login</button>
                    </form>
            </div>

            <div id="teacherDashboard" class="page">
                <div class="dashboard-header">
                    <h2>Welcome, <span id="teacherDashName">Teacher</span>!</h2>
                    <button class="logout-btn" onclick="logoutTeacher()">Logout</button>
                </div>

                <div class="dashboard-nav">
                    <button class="nav-btn active" onclick="showTeacherSection('addBehavior')">Add Behavior</button>
                    <button class="nav-btn active" onclick="showTeacherSection('viewRecords')">My Records</button>
                    <button class="nav-btn active" onclick="showTeacherSection('allStudents')">All Students</button>
                </div>

                <div id="addBehavior" class="section active">
                    <h3 style="margin-bottom: 20px;">Record Student Behavior</h3>
                    <div id="teacherAlert"></div>

                    <div class="info-card">
                        <form id="behaviorForm">
                            <div class="form-group">
                            <label for="selectStudent">Select Student *</label>
                            <select id="selectStudent" required>
                                <option value="">-- Choose a student --</option>
                            </select>
                            </div>

                            <div class="form-group">
                                <label for="behaviorDate">Date *</label>
                                <input type="date" id="behaviorDate" required>
                            </div>
                            <div class="form-group">
                                <label for="behaviorType">Behavior Type *</label>
                                <select id="behaviorType"  required>
                                    <option value="">-- Select behavior --</option>
                                    <option value="Excellent">Excellent</option>
                                    <option value="Good">Good</option>
                                    <option value="Fair">Fair</option>
                                    <option value="Poor">Poor</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="behaviorComments">Comments *</label>
                                <textarea id="behaviorComments" rows="4" required placeholder="Describe the student's behavior..."></textarea>
                            </div>
                            <button type="Submit" class="btn">Submit Behavior Record</button>
                        </form>
                    </div>
                </div>

                <div id="viewRecords" class="section">
                    <h3 style="margin-bottom: 20px">My Behavior Records</h3>
                    <div id="teacherRecordsList">Loading...</div>
                </div>
 
                <div id="allStudents" class="section">
                    <h3 style="margin-bottom: 20px;">All Students</h3>
                    <div class="table-container">
                        <table class="results-table">
                            <thead>
                                <tr>
                                    <th>Admission Number</th>
                                    <th>Full Name</th>
                                    <th>Enrollment Date</th>
                                </tr>
                            </thead>
                            <tbody id="allStudentsBody">
                                <tr>
                                    <td colspan="3" style="text-align: center;">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="index.js"></script>
</body>
</html>