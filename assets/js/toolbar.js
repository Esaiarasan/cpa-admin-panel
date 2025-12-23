document.addEventListener('DOMContentLoaded', () => {
  // -----------------------------
  // Toggle select dropdown
  // -----------------------------
  const selectBtn = document.getElementById('selectToolBtn');
  const selectDropdown = document.getElementById('selectDropdown');

  selectBtn.addEventListener('click', e => {
      e.stopPropagation();
      selectDropdown.style.display = selectDropdown.style.display === 'block' ? 'none' : 'block';
  });

  // Click outside closes dropdown
  document.addEventListener('click', () => {
      selectDropdown.style.display = 'none';
  });

  // Dropdown item click
  document.querySelectorAll('#selectDropdown .dropdown-item').forEach(item => {
      item.addEventListener('click', () => {
          const pgId = item.dataset.pgId || null;
          console.log('Clicked:', item.textContent, 'PG_ID:', pgId);
          // TODO: Add action for this item
      });
  });

  // -----------------------------
  // Toolbar actions for other icons
  // -----------------------------
  document.querySelectorAll('.toolbar-icon').forEach(btn => {
      btn.addEventListener('click', () => {
          const action = btn.dataset.action;
          handleToolbarAction(action);
      });
  });
});

// Action functions
function handleToolbarAction(action) {
  switch(action) {
      case 'select': console.log('Select clicked'); break;
      case 'help-outline': console.log('Help outline clicked'); break;
      case 'help': console.log('Help clicked'); break;
      case 'branch': console.log('Branch clicked'); break;
      case 'fork': console.log('Fork clicked'); break;
      case 'code': console.log('Code clicked'); break;
      case 'delete': console.log('Delete clicked'); break;
      case 'edit': console.log('Edit clicked'); break;
      case 'support': console.log('Support clicked'); break;
      case 'scan': console.log('Scan clicked'); break;
      case 'info': console.log('Info clicked'); break;
      case 'upload': console.log('Upload clicked'); break;
      case 'undo': console.log('Undo clicked'); break;
      case 'circle': console.log('Circle clicked'); break;
      case 'alert': console.log('Alert clicked'); break;
      case 'link': console.log('Link clicked'); break;
      case 'key': console.log('Key clicked'); break;
      case 'document': console.log('Document clicked'); break;
      default: console.warn('No action defined for', action);
  }
}


function initToolbarActions() {
  // Dropdown toggle
  const selectBtn = document.getElementById('selectToolBtn');
  const selectDropdown = document.getElementById('selectDropdown');

  if (selectBtn && selectDropdown) {
      selectBtn.addEventListener('click', e => {
          e.stopPropagation();
          selectDropdown.style.display = selectDropdown.style.display === 'block' ? 'none' : 'block';
      });

      document.addEventListener('click', () => {
          selectDropdown.style.display = 'none';
      });

      document.querySelectorAll('#selectDropdown .dropdown-item').forEach(item => {
          item.addEventListener('click', () => {
              const pgId = item.dataset.pgId || null;
              console.log('Clicked:', item.textContent, 'PG_ID:', pgId);
              // TODO: Handle click, e.g., load pathway details dynamically
          });
      });
  }

  // Other toolbar actions
  document.querySelectorAll('.toolbar-icon').forEach(btn => {
      btn.addEventListener('click', () => {
          const action = btn.dataset.action;
          handleToolbarAction(action);
      });
  });
}

// Call this initially in toolbar.php if loaded normally
if (typeof initToolbarActions === 'function') {
  initToolbarActions();
}
