const API_URL = 'api/';

// Utility helpers
function el(id) { return document.getElementById(id); }
function safe(fn) { try { fn(); } catch (e) { console.error(e); } }

function showPage(pageId) {
    document.querySelectorAll('.page').forEach(page => page.classList.remove('active'));
    const page = el(pageId);
    if (page) page.classList.add('active');
    clearAlerts();
}

function clearAlerts() {
    const alertIds = ['studentLoginAlert', 'studentRegAlert', 'parentAlert',
        'parentRegAlert', 'teacherLoginAlert', 'teacherAlert'
    ];
    alertIds.forEach(id => {
        const a = el(id);
        if (a) a.innerHTML = '';
    });
}

// safe event-attachment
function on(id, evt, handler) {
    const node = el(id);
    if (node) node.addEventListener(evt, handler);
}

// Student: login
on('studentLoginForm', 'submit', async function (e) {
    e.preventDefault();
    const admission = el('studentAdmission') ? el('studentAdmission').value.trim() : '';
    const password = el('studentPassword') ? el('studentPassword').value : '';

    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('admission_number', admission);
    formData.append('password', password);

    try {
        const res = await fetch(API_URL + 'student/auth.php', { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ success: false, message: 'Invalid server response' }));
        if (data.success) {
            showPage('studentDashboard');
            safe(loadStudentData);
            safe(loadStudentResults);
            safe(loadStudentBehavior);
            safe(loadStudentPayments);
        } else {
            showAlert('studentLoginAlert', data.message || 'Login failed', 'error');
        }
    } catch (error) {
        console.error('Student login error:', error);
        showAlert('studentLoginAlert', 'Network error. Try again.', 'error');
    }
});

// Student: register
on('studentRegisterForm', 'submit', async function (e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('action', 'register');
    formData.append('admission_number', el('regStudentAdmission') ? el('regStudentAdmission').value : '');
    formData.append('full_name', el('regStudentName') ? el('regStudentName').value : '');
    formData.append('date_of_birth', el('regStudentDOB') ? el('regStudentDOB').value : '');
    formData.append('date_of_enrollment', el('regStudentEnrollment') ? el('regStudentEnrollment').value : '');
    formData.append('parent_name', el('regParentName') ? el('regParentName').value : '');
    formData.append('parent_phone', el('regParentPhone') ? el('regParentPhone').value : '');
    formData.append('parent_email', el('regParentEmail') ? el('regParentEmail').value : '');
    formData.append('password', el('regStudentPassword') ? el('regStudentPassword').value : '');

    try {
        const res = await fetch(API_URL + 'student/auth.php', { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ success: false, message: 'Invalid server response' }));
        if (data.success) {
            showAlert('studentRegAlert', 'Registration successful. You may now login.', 'success');
            showPage('studentLogin');
        } else {
            showAlert('studentRegAlert', data.message || 'Registration failed', 'error');
        }
    } catch (error) {
        console.error('Student register error:', error);
        showAlert('studentRegAlert', 'Network error. Try again.', 'error');
    }
});

// Student loaders (defensive; won't throw and will show placeholders)
async function loadStudentData() {
    try {
        // example fetch - adapt to your API
        const res = await fetch(API_URL + 'student/profile.php', { method: 'GET' });
        const data = await res.json().catch(() => null);
        if (!data || !data.success) return;
        const d = data.data;
        if (el('spName')) el('spName').textContent = d.full_name || 'N/A';
        if (el('spAdmission')) el('spAdmission').textContent = d.admission_number || 'N/A';
        if (el('spDOB')) el('spDOB').textContent = formatDate(d.date_of_birth || '');
        if (el('spEnrollment')) el('spEnrollment').textContent = formatDate(d.date_of_enrollment || '');
        if (el('spParentName')) el('spParentName').textContent = d.parent_name || 'N/A';
        if (el('spParentPhone')) el('spParentPhone').textContent = d.parent_phone || 'N/A';
        if (el('spParentEmail')) el('spParentEmail').textContent = d.parent_email || 'N/A';
    } catch (error) {
        console.error('Error loading student data:', error);
    }
}

async function loadStudentResults() {
    try {
        const res = await fetch(API_URL + 'student/results.php');
        const data = await res.json().catch(() => null);
        const tbody = el('studentResultsBody');
        if (!tbody) return;
        if (!data || !data.success || !data.data || data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No results found.</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        data.data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${row.subject}</td><td>${row.term1 ?? '-'}</td><td>${row.term2 ?? '-'}</td><td>${row.term3 ?? '-'}</td><td>${row.average ?? '-'}</td><td>${row.grade ?? '-'}</td>`;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Error loading results:', error);
    }
}

async function loadStudentBehavior() {
    try {
        const res = await fetch(API_URL + 'student/behavior.php');
        const data = await res.json().catch(() => null);
        const container = el('studentBehaviorList');
        if (!container) return;
        if (!data || !data.success || !data.data || data.data.length === 0) {
            container.innerHTML = '<p>No behavior records found.</p>';
            return;
        }
        container.innerHTML = '';
        data.data.forEach(record => {
            const entry = `
                <div class="behavior-entry">
                    <div class="behavior-header">
                        <span class="behavior-date">📅 ${formatDate(record.behavior_date)}</span>
                        <span class="status-badge status-${(record.behavior_type||'').toLowerCase()}">${record.behavior_type}</span>
                    </div>
                    <div class="behavior-text">${record.comments || ''}</div>
                    <small style="color: #888; margin-top: 10px; display: block;">Teacher: ${record.teacher_name || 'N/A'}</small>
                </div>
            `;
            container.innerHTML += entry;
        });
    } catch (error) {
        console.error('Error loading behavior:', error);
    }
}

async function loadStudentPayments() {
    try {
        const res = await fetch(API_URL + 'student/payments.php');
        const data = await res.json().catch(() => null);
        if (!data || !data.success || !data.data) return;
        if (el('sfTotal')) el('sfTotal').textContent = formatNumber(data.summary.total || 0);
        if (el('sfPaid')) el('sfPaid').textContent = formatNumber(data.summary.paid || 0);
        if (el('sfBalance')) el('sfBalance').textContent = formatNumber(data.summary.balance || 0);
        if (el('sfStatus')) el('sfStatus').textContent = data.summary.status || 'N/A';

        const tbody = el('studentPaymentsBody');
        if (tbody) {
            tbody.innerHTML = '';
            (data.data || []).forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${formatDate(p.date)}</td><td>${formatNumber(p.amount)}</td><td>${p.method || '-'}</td><td>${p.reference || '-'}</td>`;
                tbody.appendChild(tr);
            });
        }
    } catch (error) {
        console.error('Error loading payments:', error);
    }
}

// Student UI nav (works without passing event explicitly)
function showStudentSection(sectionId) {
    document.querySelectorAll('#studentDashboard .section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('#studentDashboard .nav-btn').forEach(b => b.classList.remove('active'));

    const section = el(sectionId);
    if (section) section.classList.add('active');

    // mark clicked button active if available (document.activeElement usually the button)
    const activeBtn = document.activeElement && document.activeElement.matches && document.activeElement.matches('.nav-btn') ? document.activeElement : null;
    if (activeBtn) activeBtn.classList.add('active');
}

// Logout
function logoutStudent() {
    fetch(API_URL + 'student/auth.php', {
        method: 'POST',
        body: new URLSearchParams({ action: 'logout' })
    }).finally(() => showPage('homePage'));
}

// Parent handlers
on('parentLoginForm', 'submit', async function (e) {
    e.preventDefault();
    const phone = el('parentPhone') ? el('parentPhone').value.trim() : '';
    const password = el('parentPassword') ? el('parentPassword').value : '';

    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('phone_number', phone);
    formData.append('password', password);

    try {
        const res = await fetch(API_URL + 'parent/auth.php', { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ success: false }));
        if (data.success) {
            showPage('parentDashboard');
            safe(loadParentData);
            safe(loadParentResults);
            safe(loadParentBehavior);
        } else {
            showAlert('parentLoginAlert', data.message || 'Login failed', 'error');
        }
    } catch (error) {
        console.error('Parent login error:', error);
        showAlert('parentLoginAlert', 'Network error', 'error');
    }
});

on('parentRegisterForm', 'submit', async function (e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('action', 'register');
    formData.append('full_name', el('regParFullName') ? el('regParFullName').value : '');
    formData.append('phone_number', el('regParPhone') ? el('regParPhone').value : '');
    formData.append('email', el('regParEmail') ? el('regParEmail').value : '');
    formData.append('student_admission', el('regChildAdmission') ? el('regChildAdmission').value : '');
    formData.append('password', el('regParPassword') ? el('regParPassword').value : '');

    try {
        const res = await fetch(API_URL + 'parent/auth.php', { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ success: false }));
        if (data.success) {
            showAlert('parentRegAlert', 'Registered. Please login.', 'success');
            showPage('parentLogin');
        } else {
            showAlert('parentRegAlert', data.message || 'Registration failed', 'error');
        }
    } catch (error) {
        console.error('Parent register error:', error);
        showAlert('parentRegAlert', 'Network error', 'error');
    }
});

async function loadParentData() {
    try {
        const res = await fetch(API_URL + 'parent/profile.php');
        const data = await res.json().catch(() => null);
        if (!data || !data.success) return;
        const d = data.data;
        if (el('ppName')) el('ppName').textContent = d.full_name || 'N/A';
        if (el('ppAdmission')) el('ppAdmission').textContent = d.admission_number || 'N/A';
        if (el('ppDOB')) el('ppDOB').textContent = formatDate(d.date_of_birth || '');
        if (el('ppEnrollment')) el('ppEnrollment').textContent = formatDate(d.date_of_enrollment || '');
    } catch (error) {
        console.error('Error loading parent data:', error);
    }
}

async function loadParentResults() {
    try {
        const res = await fetch(API_URL + 'parent/results.php');
        const data = await res.json().catch(() => null);
        const tbody = el('parentResultsBody');
        if (!tbody) return;
        if (!data || !data.success || !data.data || data.data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No results found.</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        data.data.forEach(row => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${row.subject}</td><td>${row.term1 ?? '-'}</td><td>${row.term2 ?? '-'}</td><td>${row.term3 ?? '-'}</td><td>${row.average ?? '-'}</td><td>${row.grade ?? '-'}</td>`;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Error loading parent results:', error);
    }
}

async function loadParentBehavior() {
    try {
        const res = await fetch(API_URL + 'parent/behavior.php');
        const data = await res.json().catch(() => null);
        const container = el('parentBehaviorList');
        if (!container) return;
        if (!data || !data.success || !data.data || data.data.length === 0) {
            container.innerHTML = '<p>No behavior records found.</p>';
            return;
        }
        container.innerHTML = '';
        data.data.forEach(record => {
            const entry = `
                <div class="behavior-entry">
                    <div class="behavior-header">
                        <span class="behavior-date">📅 ${formatDate(record.behavior_date)}</span>
                        <span class="status-badge status-${(record.behavior_type||'').toLowerCase()}">${record.behavior_type}</span>
                    </div>
                    <div class="behavior-text">${record.comments || ''}</div>
                    <small style="color: #888; margin-top: 10px; display: block;">Teacher: ${record.teacher_name || 'N/A'}</small>
                </div>
            `;
            container.innerHTML += entry;
        });
    } catch (error) {
        console.error('Error loading parent behavior:', error);
    }
}

function showParentSection(sectionId) {
    document.querySelectorAll('#parentDashboard .section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('#parentDashboard .nav-btn').forEach(b => b.classList.remove('active'));
    const section = el(sectionId);
    if (section) section.classList.add('active');
    const activeBtn = document.activeElement && document.activeElement.matches && document.activeElement.matches('.nav-btn') ? document.activeElement : null;
    if (activeBtn) activeBtn.classList.add('active');
}

function logoutParent() {
    fetch(API_URL + 'parent/auth.php', {
        method: 'POST',
        body: new URLSearchParams({ action: 'logout' })
    }).finally(() => showPage('homePage'));
}

// Teacher handlers
on('teacherLoginForm', 'submit', async function (e) {
    e.preventDefault();
    const email = el('teacherEmail') ? el('teacherEmail').value.trim() : '';
    const password = el('teacherPassword') ? el('teacherPassword').value : '';

    const formData = new FormData();
    formData.append('action', 'login');
    formData.append('email', email);
    formData.append('password', password);

    try {
        const res = await fetch(API_URL + 'teacher/auth.php', { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ success: false }));
        if (data.success) {
            showPage('teacherDashboard');
            safe(loadTeacherData);
        } else {
            showAlert('teacherLoginAlert', data.message || 'Login failed', 'error');
        }
    } catch (error) {
        console.error('Teacher login error:', error);
        showAlert('teacherLoginAlert', 'Network error', 'error');
    }
});

on('teacherLoginForm', 'submit', function () { /* already handled above */ });

async function loadTeacherData() {
    try {
        const res = await fetch(API_URL + 'teacher/profile.php');
        const data = await res.json().catch(() => null);
        if (!data || !data.success) return;
        if (el('teacherDashName')) el('teacherDashName').textContent = data.data.full_name || 'Teacher';
        // load teacher records/students as needed
        safe(loadTeacherRecords);
        safe(loadAllStudents);
    } catch (error) {
        console.error('Error loading teacher data:', error);
    }
}

function showTeacherSection(sectionId) {
    document.querySelectorAll('#teacherDashboard .section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('#teacherDashboard .nav-btn').forEach(b => b.classList.remove('active'));
    const section = el(sectionId);
    if (section) section.classList.add('active');
    const activeBtn = document.activeElement && document.activeElement.matches && document.activeElement.matches('.nav-btn') ? document.activeElement : null;
    if (activeBtn) activeBtn.classList.add('active');
}

function logoutTeacher() {
    fetch(API_URL + 'teacher/auth.php', {
        method: 'POST',
        body: new URLSearchParams({ action: 'logout' })
    }).finally(() => showPage('homePage'));
}

// teacher placeholders
async function loadTeacherRecords() {
    try {
        const res = await fetch(API_URL + 'teacher/records.php');
        const data = await res.json().catch(() => null);
        const container = el('teacherRecordsList');
        if (!container) return;
        container.innerHTML = (data && data.success && data.data && data.data.length) ? data.data.map(r => `<div class="behavior-entry"><strong>${formatDate(r.date)}</strong> - ${r.comments}</div>`).join('') : '<p>No records yet.</p>';
    } catch (e) { console.error(e); }
}

async function loadAllStudents() {
    try {
        const res = await fetch(API_URL + 'teacher/students.php');
        const data = await res.json().catch(() => null);
        const tbody = el('allStudentsBody');
        if (!tbody) return;
        if (!data || !data.success || !data.data || !data.data.length) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;">No students found.</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        data.data.forEach(s => {
            const tr = document.createElement('tr');
            tr.innerHTML = `<td>${s.admission_number}</td><td>${s.full_name}</td><td>${formatDate(s.date_of_enrollment)}</td>`;
            tbody.appendChild(tr);
        });
        // populate selectStudent used in behavior form
        const sel = el('selectStudent');
        if (sel) {
            sel.innerHTML = '<option value="">-- Choose a student --</option>';
            data.data.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.admission_number;
                opt.textContent = `${s.admission_number} — ${s.full_name}`;
                sel.appendChild(opt);
            });
        }
    } catch (e) { console.error(e); }
}

// Behavior submission by teacher
on('behaviorForm', 'submit', async function (e) {
    e.preventDefault();
    const formData = new FormData();
    formData.append('action', 'add');
    formData.append('admission_number', el('selectStudent') ? el('selectStudent').value : '');
    formData.append('behavior_date', el('behaviorDate') ? el('behaviorDate').value : '');
    formData.append('behavior_type', el('behaviorType') ? el('behaviorType').value : '');
    formData.append('comments', el('behaviorComments') ? el('behaviorComments').value : '');

    try {
        const res = await fetch(API_URL + 'teacher/behavior.php', { method: 'POST', body: formData });
        const data = await res.json().catch(() => ({ success: false }));
        if (data.success) {
            showAlert('teacherAlert', 'Behavior recorded.', 'success');
            safe(loadTeacherRecords);
        } else {
            showAlert('teacherAlert', data.message || 'Failed to record behavior', 'error');
        }
    } catch (error) {
        console.error('Behavior submit error:', error);
        showAlert('teacherAlert', 'Network error', 'error');
    }
});

// Utilities
function formatDate(dateString) {
    if (!dateString) return '';
    const d = new Date(dateString);
    if (isNaN(d)) return dateString;
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return d.toLocaleDateString(undefined, options);
}

function formatNumber(number) {
    return new Intl.NumberFormat().format(Number(number || 0));
}

function showAlert(alertId, message, type = 'info') {
    const box = el(alertId);
    if (!box) return;
    box.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
}