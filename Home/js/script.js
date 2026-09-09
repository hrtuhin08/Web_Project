/**
 * UIU HOSTEL MANAGEMENT SYSTEM - CLIENT-SIDE ENGINE
 * Zero-backend pure JavaScript data store, authentication, and UI helpers
 */

(function () {
  'use strict';

  // Namespace
  window.HMS = window.HMS || {};

  // Default Seed Data
  const DEFAULT_DATA = {
    currentUser: {
      id: 'USR-001',
      name: 'System Administrator',
      email: 'admin@uiu.ac.bd',
      role: 'admin',
      avatar: 'AD'
    },
    users: [
      { id: 'USR-001', name: 'Dr. Rafiqul Islam', email: 'admin@uiu.ac.bd', role: 'admin', dept: 'Administration', phone: '+8801711223344', status: 'Active' },
      { id: 'USR-002', name: 'Kabir Ahmed', email: 'manager@uiu.ac.bd', role: 'manager', dept: 'Hostel Affairs', phone: '+8801811223344', status: 'Active' },
      { id: 'USR-003', name: 'Nurul Huda', email: 'mess@uiu.ac.bd', role: 'mess', dept: 'Mess Management', phone: '+8801911223344', status: 'Active' },
      { id: 'USR-004', name: 'Fahim Rahman', email: 'boarder@uiu.ac.bd', studentId: '011211045', role: 'boarder', dept: 'CSE', room: 'Padma-A-302', bed: 'Bed-1', phone: '+8801611223344', status: 'Active' },
      { id: 'USR-005', name: 'Sultana Parvin', email: 'sultana@uiu.ac.bd', studentId: '011211089', role: 'boarder', dept: 'EEE', room: 'Meghna-204', bed: 'Bed-2', phone: '+8801511223344', status: 'Active' },
      { id: 'USR-006', name: 'Tanvir Hossain', email: 'tanvir@uiu.ac.bd', studentId: '011212012', role: 'boarder', dept: 'BBA', room: 'Padma-B-105', bed: 'Bed-3', phone: '+8801711889900', status: 'Active' },
      { id: 'USR-007', name: 'Anika Tabassum', email: 'anika@uiu.ac.bd', studentId: '011213054', role: 'boarder', dept: 'Pharmacy', room: 'Meghna-101', bed: 'Bed-1', phone: '+8801811445566', status: 'Active' },
      { id: 'USR-008', name: 'Imtiaz Shovon', email: 'imtiaz@uiu.ac.bd', studentId: '011212078', role: 'boarder', dept: 'Data Science', room: 'Padma-A-401', bed: 'Bed-2', phone: '+8801911778899', status: 'Active' }
    ],
    rooms: [
      { id: 'R-101', hostel: 'Padma International', block: 'Block A', floor: '1st Floor', roomNo: 'A-101', type: 'Double Occupancy', totalBeds: 2, occupiedBeds: 2, fee: 4500, status: 'Full' },
      { id: 'R-102', hostel: 'Padma International', block: 'Block A', floor: '1st Floor', roomNo: 'A-102', type: 'Double Occupancy', totalBeds: 2, occupiedBeds: 1, fee: 4500, status: 'Available' },
      { id: 'R-201', hostel: 'Padma International', block: 'Block A', floor: '2nd Floor', roomNo: 'A-201', type: 'Single Suite', totalBeds: 1, occupiedBeds: 1, fee: 7500, status: 'Full' },
      { id: 'R-202', hostel: 'Padma International', block: 'Block A', floor: '2nd Floor', roomNo: 'A-202', type: 'Single Suite', totalBeds: 1, occupiedBeds: 0, fee: 7500, status: 'Available' },
      { id: 'R-301', hostel: 'Padma International', block: 'Block B', floor: '3rd Floor', roomNo: 'B-301', type: 'Triple Shared', totalBeds: 3, occupiedBeds: 3, fee: 3500, status: 'Full' },
      { id: 'R-302', hostel: 'Padma International', block: 'Block B', floor: '3rd Floor', roomNo: 'B-302', type: 'Triple Shared', totalBeds: 3, occupiedBeds: 2, fee: 3500, status: 'Available' },
      { id: 'R-401', hostel: 'Meghna Scholars Hall', block: 'Main Wing', floor: '1st Floor', roomNo: 'M-101', type: 'Quad Dormitory', totalBeds: 4, occupiedBeds: 4, fee: 2800, status: 'Full' },
      { id: 'R-402', hostel: 'Meghna Scholars Hall', block: 'Main Wing', floor: '1st Floor', roomNo: 'M-102', type: 'Quad Dormitory', totalBeds: 4, occupiedBeds: 2, fee: 2800, status: 'Available' },
      { id: 'R-501', hostel: 'Meghna Scholars Hall', block: 'Main Wing', floor: '2nd Floor', roomNo: 'M-201', type: 'Double Occupancy', totalBeds: 2, occupiedBeds: 1, fee: 4500, status: 'Available' }
    ],
    boarders: [
      { id: 'B-001', name: 'Fahim Rahman', studentId: '011211045', dept: 'CSE', room: 'A-302', bed: 'Bed-1', guardian: 'Abdur Rahman (+8801700112233)', phone: '+8801611223344', dues: 0, status: 'Active' },
      { id: 'B-002', name: 'Sultana Parvin', studentId: '011211089', dept: 'EEE', room: 'M-101', bed: 'Bed-2', guardian: 'M. Parvin (+8801700223344)', phone: '+8801511223344', dues: 4500, status: 'Active' },
      { id: 'B-003', name: 'Tanvir Hossain', studentId: '011212012', dept: 'BBA', room: 'B-301', bed: 'Bed-3', guardian: 'Faruk Hossain (+8801700334455)', phone: '+8801711889900', dues: 0, status: 'Active' },
      { id: 'B-004', name: 'Anika Tabassum', studentId: '011213054', dept: 'Pharmacy', room: 'M-102', bed: 'Bed-1', guardian: 'S. Tabassum (+8801700445566)', phone: '+8801811445566', dues: 0, status: 'Active' },
      { id: 'B-005', name: 'Imtiaz Shovon', studentId: '011212078', dept: 'Data Science', room: 'A-101', bed: 'Bed-2', guardian: 'Jahangir Alam (+8801700556677)', phone: '+8801911778899', dues: 2800, status: 'Active' },
      { id: 'B-006', name: 'Nadia Afrin', studentId: '011211102', dept: 'Civil Eng.', room: 'M-201', bed: 'Bed-1', guardian: 'K. Afrin (+8801700667788)', phone: '+8801311556677', dues: 0, status: 'Active' }
    ],
    applications: [
      { id: 'APP-101', studentName: 'Mahmudul Hasan', studentId: '011221004', dept: 'CSE', hostel: 'Padma International', roomType: 'Double Occupancy', applyDate: '2026-09-02', status: 'Pending' },
      { id: 'APP-102', studentName: 'Ayesha Siddiqua', studentId: '011221019', dept: 'EEE', hostel: 'Meghna Scholars Hall', roomType: 'Single Suite', applyDate: '2026-09-04', status: 'Approved' },
      { id: 'APP-103', studentName: 'Rashed Karim', studentId: '011221088', dept: 'BBA', hostel: 'Padma International', roomType: 'Triple Shared', applyDate: '2026-09-05', status: 'Pending' },
      { id: 'APP-104', studentName: 'Maliha Rahman', studentId: '011221120', dept: 'Pharmacy', hostel: 'Meghna Scholars Hall', roomType: 'Quad Dormitory', applyDate: '2026-08-28', status: 'Approved' },
      { id: 'APP-105', studentName: 'Sadman Sakib', studentId: '011221145', dept: 'CSE', hostel: 'Padma International', roomType: 'Double Occupancy', applyDate: '2026-08-25', status: 'Rejected' }
    ],
    payments: [
      { id: 'INV-2026-001', studentId: '011211045', studentName: 'Fahim Rahman', room: 'A-302', type: 'Seat Rent & Mess Fee', amount: 8200, date: '2026-09-01', method: 'bKash Online', status: 'Paid' },
      { id: 'INV-2026-002', studentId: '011211089', studentName: 'Sultana Parvin', room: 'M-101', type: 'September Room Rent', amount: 4500, date: '2026-09-05', method: 'Pending', status: 'Due' },
      { id: 'INV-2026-003', studentId: '011212012', studentName: 'Tanvir Hossain', room: 'B-301', type: 'Monthly Dining Deposit', amount: 3500, date: '2026-09-02', method: 'Nagad Gateway', status: 'Paid' },
      { id: 'INV-2026-004', studentId: '011213054', studentName: 'Anika Tabassum', room: 'M-102', type: 'Seat Rent & Utilities', amount: 5100, date: '2026-09-03', method: 'Bank Transfer', status: 'Paid' },
      { id: 'INV-2026-005', studentId: '011212078', studentName: 'Imtiaz Shovon', room: 'A-101', type: 'Maintenance & Wi-Fi', amount: 2800, date: '2026-09-07', method: 'Pending', status: 'Due' }
    ],
    complaints: [
      { id: 'CMP-201', studentName: 'Fahim Rahman', room: 'A-302', category: 'Electrical', subject: 'Ceiling Fan Regulator Not Responding', date: '2026-09-08', priority: 'Medium', status: 'In Progress' },
      { id: 'CMP-202', studentName: 'Sultana Parvin', room: 'M-101', category: 'Plumbing', subject: 'Washroom 2nd Floor Tap Leakage', date: '2026-09-07', priority: 'High', status: 'Resolved' },
      { id: 'CMP-203', studentName: 'Tanvir Hossain', room: 'B-301', category: 'Wi-Fi', subject: 'Optical Fiber Disconnection in Wing B', date: '2026-09-09', priority: 'High', status: 'In Progress' },
      { id: 'CMP-204', studentName: 'Anika Tabassum', room: 'M-102', category: 'Dining/Mess', subject: 'Dinner Rice Quality Feedback', date: '2026-09-05', priority: 'Low', status: 'Resolved' },
      { id: 'CMP-205', studentName: 'Imtiaz Shovon', room: 'A-101', category: 'Carpentry', subject: 'Study Chair Wheel Loose', date: '2026-09-09', priority: 'Low', status: 'Pending' }
    ],
    notices: [
      { id: 'NTC-01', title: 'Fall 2026 Seat Allocation & Check-in Schedule', date: '2026-09-01', priority: 'Urgent', target: 'All Residents', author: 'Hostel Provost Office', content: 'Approved boarders must complete biometric room registration at the hostel desk by September 15. Bring university ID card.' },
      { id: 'NTC-02', title: 'Strict 9:00 PM Mess Meal Lock Policy Reminder', date: '2026-09-04', priority: 'Normal', target: 'All Boarders', author: 'Mess Committee', content: 'Residents must toggle their lunch and dinner preference via the portal before 9:00 PM. No meal status changes permitted post-cutoff.' },
      { id: 'NTC-03', title: 'High-Speed Wi-Fi Router Maintenance on Friday', date: '2026-09-06', priority: 'Normal', target: 'Wing B & Main Block', author: 'IT Helpdesk', content: 'Scheduled firmware upgrades from 2:00 PM to 4:00 PM. Backup cellular 4G available in ground floor study lounge.' },
      { id: 'NTC-04', title: 'Emergency Fire Drill & Evacuation Practice', date: '2026-09-08', priority: 'Urgent', target: 'All Hostels', author: 'Security Office', content: 'Mandatory fire evacuation simulation will be conducted on Saturday at 11:00 AM.' }
    ],
    weeklyMenu: [
      { day: 'Monday', breakfast: 'Paratha, Daal Butter, Boiled Egg, Tea', lunch: 'Fine Rice, Rui Fish Curry, Mixed Veg, Daal', dinner: 'Steamed Rice, Desi Chicken Curry, Salad, Daal' },
      { day: 'Tuesday', breakfast: 'Khichuri, Omelet, Green Pickle, Tea', lunch: 'Rice, Beef / Mutton Bhuna, Tomato Chutney, Daal', dinner: 'Rice, Pangas/Telapia Curry, Potato Mash, Daal' },
      { day: 'Wednesday', breakfast: 'Bread, Butter/Jam, Scrambled Egg, Banana, Tea', lunch: 'Rice, Chicken Roast, Polao Rice, Cucumber Salad', dinner: 'Roti / Rice, Egg Curry, Cabbage Bhaji, Daal' },
      { day: 'Thursday', breakfast: 'Paratha, Chola Bhuna, Boiled Egg, Tea', lunch: 'Rice, Katla Fish Curry, Spinach (Palong Shak), Daal', dinner: 'Rice, Chicken Jhal Fry, Vegetable Labra, Daal' },
      { day: 'Friday', breakfast: 'Shahi Halwa, Puri, Masala Tea', lunch: 'Special Beef / Chicken Kacchi Biryani, Borhani', dinner: 'Khichuri, Fried Hilsha / Egg, Achar, Daal' },
      { day: 'Saturday', breakfast: 'Paratha, Vegetable Curry, Egg, Tea', lunch: 'Rice, Pabda / Rui Curry, Gourd with Prawn, Daal', dinner: 'Rice, Broiler Chicken Curry, Lentil Soup' },
      { day: 'Sunday', breakfast: 'French Toast, Boiled Egg, Coffee / Tea', lunch: 'Rice, Chicken Curry with Potatoes, Aubergine Bhaji', dinner: 'Rice, Mixed Veg Curry, Big Fish Curry, Daal' }
    ],
    mealRate: 85,
    cutoffTime: '21:00'
  };

  // Initialize LocalStorage Data Store
  HMS.init = function () {
    for (let key in DEFAULT_DATA) {
      if (!localStorage.getItem('HMS_' + key)) {
        localStorage.setItem('HMS_' + key, JSON.stringify(DEFAULT_DATA[key]));
      }
    }
  };

  // Getter & Setter
  HMS.get = function (key) {
    const raw = localStorage.getItem('HMS_' + key);
    try {
      return raw ? JSON.parse(raw) : DEFAULT_DATA[key];
    } catch (e) {
      return DEFAULT_DATA[key];
    }
  };

  HMS.set = function (key, value) {
    localStorage.setItem('HMS_' + key, JSON.stringify(value));
  };

  // Authentication Helpers
  HMS.login = function (email, role) {
    const users = HMS.get('users');
    let user = users.find(u => u.email.toLowerCase() === email.toLowerCase());
    if (!user) {
      user = {
        id: 'USR-' + Math.floor(100 + Math.random() * 900),
        name: role.charAt(0).toUpperCase() + role.slice(1) + ' User',
        email: email,
        role: role,
        avatar: role.slice(0, 2).toUpperCase()
      };
    }
    HMS.set('currentUser', user);
    HMS.showToast('Login successful! Welcome, ' + user.name, 'success');
    
    // Determine path based on role
    setTimeout(() => {
      window.location.href = role + '/dashboard.html';
    }, 500);
  };

  HMS.logout = function () {
    HMS.set('currentUser', null);
    HMS.showToast('Signed out successfully', 'info');
    setTimeout(() => {
      // Determine relative path to login
      const isInSubdir = window.location.pathname.includes('/admin/') || 
                         window.location.pathname.includes('/manager/') || 
                         window.location.pathname.includes('/mess/') || 
                         window.location.pathname.includes('/boarder/');
      window.location.href = isInSubdir ? '../login.html' : 'login.html';
    }, 400);
  };

  HMS.getCurrentUser = function () {
    return HMS.get('currentUser') || DEFAULT_DATA.currentUser;
  };

  // Toast Notification System
  HMS.showToast = function (message, type = 'info') {
    let container = document.getElementById('toast-container');
    if (!container) {
      container = document.createElement('div');
      container.id = 'toast-container';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = 'toast ' + type;
    toast.innerHTML = `<span>${type === 'success' ? '✓' : type === 'danger' ? '✕' : 'ℹ'}</span> <div>${message}</div>`;
    container.appendChild(toast);

    setTimeout(() => {
      if (toast.parentNode) toast.parentNode.removeChild(toast);
    }, 4000);
  };

  // Modal Dialogs
  HMS.openModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.add('active');
  };

  HMS.closeModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove('active');
  };

  // Table Search Filter
  HMS.filterTable = function (inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;

    input.addEventListener('input', function () {
      const term = input.value.toLowerCase();
      const rows = table.querySelectorAll('tbody tr');
      rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
      });
    });
  };

  // Shell Setup (Topbar user avatar, sidebar toggle)
  HMS.setupShell = function (activePageTitle) {
    const user = HMS.getCurrentUser();
    
    // Set user names and avatars
    const userNames = document.querySelectorAll('.user-name-display');
    userNames.forEach(el => el.textContent = user.name || 'User');

    const userRoles = document.querySelectorAll('.user-role-display');
    userRoles.forEach(el => el.textContent = (user.role ? user.role.toUpperCase() : 'PORTAL'));

    const userAvatars = document.querySelectorAll('.user-avatar-display');
    userAvatars.forEach(el => {
      const initials = (user.name || 'U').split(' ').map(n => n[0]).join('').slice(0, 2);
      el.textContent = initials.toUpperCase();
    });

    // Mobile Sidebar Toggle
    const toggleBtn = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.dashboard-sidebar');
    if (toggleBtn && sidebar) {
      toggleBtn.addEventListener('click', () => {
        sidebar.classList.toggle('show');
      });

      document.addEventListener('click', (e) => {
        if (!sidebar.contains(e.target) && !toggleBtn.contains(e.target) && sidebar.classList.contains('show')) {
          sidebar.classList.remove('show');
        }
      });
    }

    // Close Modals on Overlay Click or Esc
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
      overlay.addEventListener('click', function (e) {
        if (e.target === this) {
          this.classList.remove('active');
        }
      });
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
      }
    });
  };

  // Run initial state setup on load
  HMS.init();

})();
