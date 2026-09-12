

document.addEventListener('DOMContentLoaded', () => {
  const mealSwitches = document.querySelectorAll('.meal-switch');
  
  mealSwitches.forEach(sw => {
    sw.addEventListener('change', async function() {
      const mealDate = this.dataset.date;
      const mealType = this.dataset.meal; // breakfast, lunch, dinner
      const boarderId = this.dataset.boarderId;
      const isChecked = this.checked ? 1 : 0;
      const originalState = !this.checked;

      const now = new Date();
      const targetDate = new Date(mealDate + 'T00:00:00');
      
      const today = new Date();
      today.setHours(0,0,0,0);
      
      const tomorrow = new Date(today);
      tomorrow.setDate(tomorrow.getDate() + 1);

      if (targetDate <= today) {
        showToast('error', 'Meal changes for today or past days are closed.');
        this.checked = originalState;
        return;
      }

      if (targetDate.toDateString() === tomorrow.toDateString()) {
        const currentHour = now.getHours();
        if (currentHour >= 21) {
          showToast('error', 'Meal changes for tomorrow close at 9:00 PM.');
          this.checked = originalState;
          return;
        }
      }

      try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const baseUrl = window.BASE_URL || '';
        const response = await fetch(`${baseUrl}/actions/meal.php`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            action: 'update_preference',
            boarder_id: boarderId,
            meal_date: mealDate,
            meal_type: mealType,
            status: isChecked,
            csrf_token: csrfToken
          })
        });

        const result = await response.json();

        if (result.success) {
          showToast('success', result.message || `${mealType.charAt(0).toUpperCase() + mealType.slice(1)} preference saved!`);
        } else {
          showToast('error', result.message || 'Failed to update meal preference.');
          this.checked = originalState;
        }
      } catch (err) {
        console.error('Meal toggle error:', err);
        showToast('error', 'Network error while updating meal.');
        this.checked = originalState;
      }
    });
  });
});
