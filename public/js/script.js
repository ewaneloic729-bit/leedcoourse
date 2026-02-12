const SAMPLE_TEACHERS = [
    { id: "1", name: "Dr. Marie Dupont", email: "marie.dupont@school.edu", subject: "Mathématiques", students: 28, status: "active" },
    { id: "2", name: "Prof. Jean Bernard", email: "jean.bernard@school.edu", subject: "Français", students: 32, status: "active" },
    { id: "3", name: "Dr. Sophie Martin", email: "sophie.martin@school.edu", subject: "Physique", students: 25, status: "active" },
    { id: "4", name: "Prof. Laurent Dubois", email: "laurent.dubois@school.edu", subject: "Histoire-Géographie", students: 30, status: "active" },
    { id: "5", name: "Dr. Catherine Leclerc", email: "catherine.leclerc@school.edu", subject: "Chimie", students: 26, status: "inactive" },
    { id: "6", name: "Prof. Michel Rousseau", email: "michel.rousseau@school.edu", subject: "Éducation Physique", students: 35, status: "active" },
    { id: "7", name: "Dr. Anne Fontaine", email: "anne.fontaine@school.edu", subject: "Biologie", students: 27, status: "active" },
    { id: "8", name: "Prof. Pierre Lefebvre", email: "pierre.lefebvre@school.edu", subject: "Informatique", students: 31, status: "active" },
];

const tableBody = document.getElementById('teacherTableBody');
const searchInput = document.getElementById('searchInput');

function renderTeachers(teachers) {
    tableBody.innerHTML = '';

    if (teachers.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 3rem; color: #64748b;">Aucun enseignant trouvé pour votre recherche.</td></tr>`;
    }

    teachers.forEach(teacher => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div style="display: flex; align-items: center;">
                    <div class="avatar">${teacher.name.charAt(0)}</div>
                    <span style="font-weight: 500;">${teacher.name}</span>
                </div>
            </td>
            <td style="color: #475569; font-size: 0.875rem;">${teacher.email}</td>
            <td><span class="badge badge-blue">${teacher.subject}</span></td>
            <td style="color: #475569;">${teacher.students}</td>
            <td>
                <span class="badge ${teacher.status === 'active' ? 'badge-green' : 'badge-red'}">
                    ${teacher.status === 'active' ? 'Actif' : 'Inactif'}
                </span>
            </td>
            <td><button style="background:none; border:none; cursor:pointer; color: #64748b;">•••</button></td>
        `;
        tableBody.appendChild(row);
    });

    updateStats(teachers);
}

function updateStats(teachers) {
    document.getElementById('totalTeachers').textContent = teachers.length;
    document.getElementById('activeTeachers').textContent = teachers.filter(t => t.status === 'active').length;
    document.getElementById('totalStudents').textContent = teachers.reduce((sum, t) => sum + t.students, 0);
}

// Event listener pour la recherche
searchInput.addEventListener('input', (e) => {
    const term = e.target.value.toLowerCase();
    const filtered = SAMPLE_TEACHERS.filter(t => 
        t.name.toLowerCase().includes(term) || 
        t.subject.toLowerCase().includes(term) || 
        t.email.toLowerCase().includes(term)
    );
    renderTeachers(filtered);
});

// Initialisation
renderTeachers(SAMPLE_TEACHERS);